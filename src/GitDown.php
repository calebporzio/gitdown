<?php

namespace CalebPorzio;

class GitDown
{
    public static function parseAndCache($content, $minutes = null)
    {
        if (is_callable($minutes)) {
            return $minutes(static::generateParserCallback($content));
        } elseif (is_null($minutes)) {
            return cache()->rememberForever(sha1($content), function () use ($content) {
                return static::parse($content);
            });
        }

        return cache()->remember(sha1($content), $minutes, function () use ($content) {
            return static::parse($content);
        });
    }

    public static function generateParserCallback($content)
    {
        return function () use ($content) {
            return static::parse($content);
        };
    }

    public static function parse($content)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/markdown/raw");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/x-markdown',
            'User-Agent: GitDown Plugin',
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

        $parsed = curl_exec($ch);

        curl_close($ch);

        return $parsed;
    }

    public static function styles()
    {
        return file_get_contents(
            implode(DIRECTORY_SEPARATOR, [
                __DIR__, '..', 'dist', 'styles.css',
            ])
        );
    }
}
