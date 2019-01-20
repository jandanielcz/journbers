<?php


namespace Journbers\Tool;


class StringTool
{
    public static function nameInicials($fullName)
    {
        $inicials = array_map(function($one) {
            return substr($one,0, 1);
        }, explode(' ', $fullName));
        return join('', $inicials);
    }
}