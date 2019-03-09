<?php

namespace GitDown;

use Zttp\Zttp;

class GitDown
{
    protected $token;
    protected $context;
    protected $allowIframes;

    public function __construct($token = null, $context = null, $allowIframes = false)
    {
        $this->token = $token;
        $this->context = $context;
        $this->allowIframes = $allowIframes;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    public function withIframes()
    {
        $this->allowIframes = true;

        return $this;
    }

    public function parse($content)
    {
        $response = Zttp::withHeaders([
            'User-Agent' => 'GitDown Plugin',
        ] + ($this->token ? ['Authorization' => 'token '.$this->token] : []))
        ->post('https://api.github.com/markdown', [
            'text' => $this->encryptIframeTags($content),
        ] + ($this->context ? ['mode' => 'gfm', 'context' => $this->context] : []));

        if (! $response->isOk()) {
            throw new \Exception('GitHub API Error: ' . $response->body());
        }

        return $this->decryptIframeTags((string) $response);
    }

    public function encryptIframeTags($input)
    {
        if (! $this->allowIframes) {
            return $input;
        }

        if (! preg_match_all('/<iframe[^>]*?(?:\/>|>[^<]*?<\/iframe>)/', $input, $matches)) {
            return $input;
        };

        foreach ($matches[0] as $match) {
            $input = str_replace($match, '\[iframe\]'. base64_encode($match).'\[endiframe\]', $input);
        }

        return $input;
    }

    public function decryptIframeTags($input)
    {
        if (! $this->allowIframes) {
            return $input;
        }

        if (! preg_match_all('/\[iframe\].*\[endiframe\]/', $input, $matches)) {
            return $input;
        };

        foreach ($matches[0] as $match) {
            $input = str_replace($match, base64_decode(ltrim(rtrim($match, '[endiframe]'), '[iframe]')), $input);
        }

        return $input;
    }

    public function parseAndCache($content, $minutes = null)
    {
        if (is_null($minutes)) {
            return cache()->rememberForever(sha1($content), function () use ($content) {
                return $this->parse($content);
            });
        }

        if (is_callable($minutes)) {
            return $minutes($this->generateParseCallback($content));
        }

        return cache()->remember(sha1($content), now()->addMinutes(int $minutes), function () use ($content) {
            return $this->parse($content);
        });
    }

    protected function generateParseCallback($content)
    {
        return function () use ($content) {
            return $this->parse($content);
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
