<?php


namespace Journbers;


class Log
{
    protected $logFilePath = null;

    public function __construct($logDir)
    {
        $fullLogPath = sprintf('%s%sjournbers-%s.log', $logDir, DIRECTORY_SEPARATOR, date('Y-m-d'));
        if (!file_exists($fullLogPath)) {
            $h = fopen($fullLogPath, 'w+');
            fclose($h);
        } else {
            if (!is_writable($fullLogPath)) {
                throw new \RuntimeException('Log file not writable.');
            }
        }

        $this->logFilePath = $fullLogPath;

    }

    public function log($message, $user = null, $type = 'info')
    {
        $this->writeLog($message, $user, $type);
    }

    protected function writeLog($message, $user, $type)
    {
        $h = fopen($this->logFilePath, 'a+');
        $d = new \DateTimeImmutable();
        fputs($h, sprintf(
            "[%s] %s: (%s) %s".PHP_EOL,
            $d->format(\DateTimeImmutable::ISO8601),
            mb_strtoupper(mb_substr($type, 0, 4)),
            $user,
            $message
        ));
        fclose($h);
    }
}