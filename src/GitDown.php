<?php

namespace GitDown;

use Zttp\Zttp;

class GitDown
{
    protected $token;
    protected $context;
    protected $allowedTags;

    public function __construct($token = null, $context = null, $allowedTags = [])
    {
        $this->token = $token;
        $this->context = $context;
        $this->allowedTags = $allowedTags;
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

    public function withTags($allowedTags = [])
    {
        $this->allowedTags = $allowedTags;

        return $this;
    }

    public function parse($content)
    {
        $response = Zttp::withHeaders([
            'User-Agent' => 'GitDown Plugin',
        ] + ($this->token ? ['Authorization' => 'token '.$this->token] : []))
        ->post('https://api.github.com/markdown', [
            'text' => $this->encryptAllowedTags($content),
        ] + ($this->context ? ['mode' => 'gfm', 'context' => $this->context] : []));

        if (! $response->isOk()) {
            throw new \Exception('GitHub API Error: ' . $response->body());
        }

        return $this->decryptAllowedTags((string) $response);
    }

    public function encryptAllowedTags($input)
    {
        if (! count($this->allowedTags)) {
            return $input;
        }

        foreach ($this->allowedTags as $tag) {
            if (! preg_match_all("/<{$tag}[^>]*?(?:\/>|>[^<]*?<\/{$tag}>)/", $input, $matches)) {
                continue;
            };

            foreach ($matches[0] as $match) {
                $input = str_replace($match, "\[{$tag}\]" . base64_encode($match) . "\[end{$tag}\]", $input);
            }
        }

        return $input;
    }

    public function decryptAllowedTags($input)
    {
        if (! count($this->allowedTags)) {
            return $input;
        }

        foreach ($this->allowedTags as $tag) {

            if (! preg_match_all("/\[{$tag}\].*\[end{$tag}\]/", $input, $matches)) {
                continue;
            };

            foreach ($matches[0] as $match) {
                $input = str_replace($match, base64_decode(ltrim(rtrim($match, "[end{$tag}]"), "[{$tag}]")), $input);
            }
        }

        return $input;
    }

    public function parseAndCache($content, $minutes = null)
    {
        if (is_callable($minutes)) {
            return $minutes($this->generateParseCallback($content));
        } elseif (is_null($minutes)) {
            return cache()->rememberForever(sha1($content), function () use ($content) {
                return $this->parse($content);
            });
        }

        return cache()->remember(sha1($content), $minutes, function () use ($content) {
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
