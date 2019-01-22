<?php


namespace Journbers;


class Env
{
    protected $prefix = null;

    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    public function getVars()
    {
        $allEnvVars = array_merge(getenv(), $_ENV, $_SERVER);
        $prefix     = $this->prefix;
        $vars       = array_filter($allEnvVars, function ($v, $k) use (&$prefix) {
            return (preg_match(sprintf('#^%s#', $prefix), $k) === 1);
        }, ARRAY_FILTER_USE_BOTH);

        $removedPrefix = [];
        foreach ($vars as $k => $v) {
            $removedPrefix[preg_replace(sprintf('#^%s#', $this->prefix), '', $k)] = $v;
        }
        return $removedPrefix;
    }
}
