<?php
namespace App\Core;

class Logger
{
    private static string $logPath = __DIR__ . '/../../storage/logs/app.log';

    public static function info(string $message): void
    {
        $dir = dirname(self::$logPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [INFO] $message" . PHP_EOL;
        file_put_contents(self::$logPath, $logEntry, FILE_APPEND);
    }
}