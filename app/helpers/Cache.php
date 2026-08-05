<?php
/**
 * Server-Side File-Based Cache Helper
 * Menyediakan utilitas pencachean data berbasis file JSON dengan TTL (Time To Live).
 */

class Cache {
    private static $cacheDir = null;

    private static function init() {
        if (self::$cacheDir !== null) return;
        
        $baseDir = defined('APP_PATH') ? APP_PATH : dirname(__DIR__);
        self::$cacheDir = $baseDir . '/cache';

        if (!file_exists(self::$cacheDir)) {
            @mkdir(self::$cacheDir, 0755, true);
        }

        $gitignore = self::$cacheDir . '/.gitignore';
        if (!file_exists($gitignore)) {
            @file_put_contents($gitignore, "*\n!.gitignore\n");
        }
    }

    private static function getFilePath($key) {
        self::init();
        $hashKey = md5($key);
        return self::$cacheDir . '/' . $hashKey . '.json';
    }

    public static function get($key, $default = null) {
        $file = self::getFilePath($key);
        if (!file_exists($file)) return $default;

        $content = @file_get_contents($file);
        if (!$content) return $default;

        $payload = json_decode($content, true);
        if (!is_array($payload) || !isset($payload['expires_at'])) {
            @unlink($file);
            return $default;
        }

        if (time() > $payload['expires_at']) {
            @unlink($file);
            return $default;
        }

        return $payload['data'] ?? $default;
    }

    public static function set($key, $data, $ttlSeconds = 3600) {
        $file = self::getFilePath($key);
        $payload = [
            'key' => $key,
            'expires_at' => time() + (int)$ttlSeconds,
            'created_at' => time(),
            'data' => $data
        ];
        return @file_put_contents($file, json_encode($payload, JSON_PRETTY_PRINT)) !== false;
    }

    public static function remember($key, $ttlSeconds, callable $callback) {
        $value = self::get($key);
        if ($value !== null) {
            return $value;
        }

        $value = $callback();
        self::set($key, $value, $ttlSeconds);
        return $value;
    }

    public static function forget($key) {
        $file = self::getFilePath($key);
        if (file_exists($file)) {
            return @unlink($file);
        }
        return true;
    }

    public static function flush() {
        self::init();
        $files = glob(self::$cacheDir . '/*.json');
        if ($files) {
            foreach ($files as $file) {
                @unlink($file);
            }
        }
        return true;
    }
}
