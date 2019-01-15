<?php


namespace Journbers;


class Flash
{
    const FLASHKEY = 'qymp6czyjvarbjim';
    const PAYLOAD_KEY = 'qymp6czyjvarbjim-payload';

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

    public function addPayload($key, $payload)
    {
        if (!isset($_SESSION[self::PAYLOAD_KEY])) {
            $_SESSION[self::PAYLOAD_KEY] = [];
        }
        $_SESSION[self::PAYLOAD_KEY][$key] = serialize($payload);
    }

    public function getPayload($key)
    {
        $out = (isset($_SESSION[self::PAYLOAD_KEY]) && isset($_SESSION[self::PAYLOAD_KEY][$key])) ? $_SESSION[self::PAYLOAD_KEY][$key] : null;
        unset($_SESSION[self::PAYLOAD_KEY][$key]);
        return ($out == null) ? null : unserialize($out);
    }

}