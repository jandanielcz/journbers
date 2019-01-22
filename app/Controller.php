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

    protected function connectionParams()
    {
        return [
            'host' => $this->config->get('DB_SERVER'),
            'port' => $this->config->get('DB_PORT'),
            'dbname' => $this->config->get('DB_NAME'),
            'user' => $this->config->get('DB_USER'),
            'password' => $this->config->get('DB_PASS')
        ];
    }

    protected function exit()
    {
        exit;
    }

    protected function commonTemplateVars()
    {
        return [
            'common.appName' => $this->config()->get('appName', 'Journbers')
        ];
    }

    protected function template($name)
    {
        $t = new Template($name);
        $t->vars($this->commonTemplateVars());

        return $t;
    }
}
