<?php


namespace Journbers\Tool;


class StringTool
{
    public static function nameInicials($fullName)
    {
        $initials = array_map(function ($one) {
            return mb_substr($one, 0, 1);
        }, explode(' ', $fullName));
        return join('', $initials);
    }

    public static function durationToHtml($durationInMinutes)
    {
        $h = floor($durationInMinutes / 60);
        $m = ($durationInMinutes % 60);
        return sprintf('%s<span>H</span>%s<span>M</span>', $h, $m);
    }
}