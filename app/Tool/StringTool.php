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

    public static function durationToHtml($durationInMinutes)
    {
        $h = floor($durationInMinutes / 60);
        $m = ($durationInMinutes % 60);
        return sprintf('%s<span>H</span>%s<span>M</span>', $h, $m);
    }
}