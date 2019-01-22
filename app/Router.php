<?php


namespace Journbers;


class Router
{

    protected $routes = [];

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function match($method, $path)
    {
        $method = strtolower($method);
        $path   = strtolower($path);

        // TODO: should not access $_SERVER directly
        if (isset($_SERVER['QUERY_STRING'])) {
            $path = preg_replace('#(\?' . preg_quote($_SERVER['QUERY_STRING']) . ')$#', '', $path);
        }

        $matches = array_filter($this->routes, function ($one) use (&$method) {
            return ($one[0] === $method);
        });

        $matches = array_filter($matches, function ($one) use (&$path) {
            return (preg_match($one[1], $path) === 1);
        });

        if (empty($matches)) {
            throw new \RuntimeException('Route for this request not configured.');
        }

        return array_values($matches)[0];
    }
}
