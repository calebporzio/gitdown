<?php

namespace GitDown;

use Zttp\Zttp;

class GitDown
{
    protected $token;
    protected $context;

    public function __construct($token = null, $context = null)
    {
        $this->token = $token;
        $this->context = $context;
    }

    public function parse($content)
    {
        $response = Zttp::withHeaders([
            'User-Agent' => 'GitDown Plugin',
        ] + ($this->token ? ['Authorization' => 'token '.$this->token] : []))
        ->post('https://api.github.com/markdown', [
            'text' => $content,
        ] + ($this->context ? ['mode' => 'gfm', 'context' => $this->context] : []));

        if (! $response->isOk()) {
            throw new \Exception('GitHub API Error: ' . $response->body());
        }

        return $response;
    }

    public function parseAndCache($content, $minutes = null)
    {
        if (is_callable($minutes)) {
            return $minutes(static::generateParseCallback($content));
        } elseif (is_null($minutes)) {
            return cache()->rememberForever(sha1($content), function () use ($content) {
                return static::parse($content);
            });
        }

        return cache()->remember(sha1($content), $minutes, function () use ($content) {
            return static::parse($content);
        });
    }

    protected function generateParseCallback($content)
    {
        return function () use ($content) {
            return static::parse($content);
        };
    }

    public function styles()
    {
        return file_get_contents(
            implode(DIRECTORY_SEPARATOR, [
                __DIR__, '..', 'dist', 'styles.css',
            ])
        );
    }
}
