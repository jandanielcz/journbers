<?php


namespace Journbers;


class Flash
{
    const FLASHKEY = 'qymp6czyjvarbjim';

    public function message($message, $type = 'info')
    {
        if (!isset($_SESSION[self::FLASHKEY])) {
            $_SESSION[self::FLASHKEY] = [];
        }
        $_SESSION[self::FLASHKEY][] = [
            'text' => $message,
            'type' => $type
        ];
    }

    public function error($message)
    {
        $this->message($message, 'error');
    }

    public function getMessages()
    {
        $out = (isset($_SESSION[self::FLASHKEY])) ? $_SESSION[self::FLASHKEY] : [];
        $_SESSION[self::FLASHKEY] = [];
        return $out;
    }
}