<?php


namespace Journbers\Tool;


use Tracy\Debugger;

class ColorTool
{
    public static function stringToColor($string)
    {
        return sprintf('#%s', substr(sha1($string),0,6));
    }

    public static function isDark($hexColor)
    {
        $c = str_replace('#', '', $hexColor);
        $r = hexdec(substr($c,0,2));
        $g = hexdec(substr($c,2,2));
        $b = hexdec(substr($c,4,2));
        return (($r + $g + $b) < 380);
    }
}