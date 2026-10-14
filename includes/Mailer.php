<?php
declare(strict_types=1);
class Mailer {
    public static function sendTemplate(string $to, string $subject, string $type, array $vars = []): bool {
        $logDir = dirname(__DIR__) . '/logs';
        if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
        @file_put_contents($logDir . '/mail.log', sprintf("[%s] TO: %s | SUBJ: %s\n", date('c'), $to, $subject), FILE_APPEND);
        return true;
    }
}