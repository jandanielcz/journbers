<?php


namespace Journbers;


class Controller
{
    protected $config = null;
    protected $request = null;

    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    protected function request()
    {
        return $this->request;
    }

    protected function config()
    {
        return $this->config;
    }

    protected function redirect($path)
    {

        header(sprintf('Location: %s', $path), true, 301);
    }

    protected function exit()
    {
        exit;
    }
}