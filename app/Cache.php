<?php

namespace App;

use Carbon\Carbon;

class Cache
{
    public static function save(string $key, string $data, int $ttl = 120): void
    {
        $cacheFile = '../cache/' . $key;

        file_put_contents($cacheFile, json_encode([
            'expires_at' => Carbon::now()->addSeconds($ttl),
            'content' => $data
        ]));
    }

    public static function get(string $key): string
    {
        $content = json_decode(file_get_contents('../cache/' . $key));
        return $content->content;
    }

    public static function forget(string $key): void
    {
        unlink('../cache/' . $key);
    }

    public static function has(string $key): bool
    {
        if (!file_exists('../cache/' . $key)) {
            return false;
        }

        $content = json_decode(file_get_contents('../cache/' . $key));

        return Carbon::now() < $content->expires_at;

    }

}