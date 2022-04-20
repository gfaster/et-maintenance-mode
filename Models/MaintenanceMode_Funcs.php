<?php
declare(strict_types=1);

//namespace MaintenanceMode\Models;

class MaintenanceMode_Funcs {
    public static function Shutdown_hard(string $html, int $retryAfterXSeconds, string $message) {
        header('HTTP/1.1 503 Service Unavailable');
        header('Retry-After: '. ($retryAfterXSeconds) ); //seconds
        $html = str_replace('{{message}}',$message, $html);
        echo $html;
        exit();
    }
}