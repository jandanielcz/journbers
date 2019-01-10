<?php


namespace Journbers\Controller;


use Journbers\Controller;
use Journbers\Credentials\InvalidCredentialsException;
use Journbers\Credentials\PasswordCredentials;
use Journbers\Data;
use Journbers\Data\CredentialsStore;
use Journbers\Flash;
use Journbers\Template;
use Tracy\Debugger;

class User extends Controller
{
    public function login()
    {
        // TODO: too hardcoded
        $providedCredentials = new PasswordCredentials($_POST['User'], $_POST['Pass']);
        try {
            $cs = new CredentialsStore([
                'host' => $this->config->get('DB_SERVER'),
                'port' => $this->config->get('DB_PORT'),
                'dbname' => $this->config->get('DB_NAME'),
                'user' => $this->config->get('DB_USER'),
                'password' => $this->config->get('DB_PASS')
            ]);
            $userInfo = $providedCredentials->validate($cs);

        } catch (InvalidCredentialsException $e) {
            $f = new Flash();
            $f->error('Username or password doesn\'t match.');
            $this->redirect('/login');
            $this->exit();
        }

        $this->request()->user()->setRoles($userInfo['providedRoles']);
        $this->request()->user()->setId($userInfo['id']);
        $this->request()->user()->setFullName($userInfo['fullName']);

        $this->request()->user()->saveToSession();
        $this->redirect('/');

    }
}