<?php


namespace Journbers;


use Tracy\Debugger;

class Controller
{
    const ANY_ROLE = '86890c88-1375-4eeb-b47d-1cb7d6c4d305';

    protected $config = null;
    protected $request = null;
    protected $log = null;

    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    protected function log()
    {
        if ($this->log === null) {
            $this->log = new Log($this->config()->get('LOG_PATH'));
        }

        return $this->log;
    }

    public function checkAccess($method)
    {
        $m = [];
        preg_match('#Controller\\\(.*)$#', get_class($this), $m);

        $shortClass = $m[1];
        $accessKey = sprintf('%s::%s', ucfirst($shortClass), ucfirst($method));
        $access = $this->config()->get('access');

        if (!isset($access[$accessKey]) || empty($access[$accessKey])) {
            throw new \RuntimeException(sprintf('No allowed roles are set for %s action.', $accessKey));
        }

        $roles = $access[$accessKey];
        if (!is_array($roles)) {
            throw new \RuntimeException(
                sprintf('Allowed roles for action %s should be defined as array in config.', $accessKey)
            );
        }
        if (in_array(self::ANY_ROLE, $roles)) {
            return true;
        }

        foreach ($roles as $allowedRole) {
            if ($this->request()->user()->hasRole($allowedRole)) {
                return true;
            }
        }

        return false;
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
            'common.appName' => $this->config()->get('appName', 'Journbers'),
            'common.user' => $this->request()->user()
        ];
    }

    protected function template($name)
    {
        $t = new Template($name);
        $t->vars($this->commonTemplateVars());

        return $t;
    }

    public function messageAndRedirect($path, $message = null, $type = 'info')
    {
        if ($message !== null) {
            $f = new Flash();
            $f->message($message, $type);
        }

        $this->redirect($path);
        $this->exit();
    }
}
