<?php

namespace CalebPorzio;

class GitDown
{
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
        return file_get_contents(__DIR__ . '/../dist/styles.css');
    }
}
