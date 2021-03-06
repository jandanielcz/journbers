<?php


namespace Journbers\Controller;


use Journbers\Controller;
use Journbers\Credentials\InvalidCredentialsException;
use Journbers\Credentials\PasswordCredentials;
use Journbers\Data\CredentialsStore;
use Journbers\Flash;
use Tracy\Debugger;

class User extends Controller
{
    public function logout()
    {
        $this->request()->user()->logout();
        $this->redirect('/');
        $this->exit();
    }

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
            $this->request()->user()->setRoles($userInfo['providedRoles']);
            $this->request()->user()->setId($userInfo['id']);
            $this->request()->user()->setFullName($userInfo['fullName']);
            $this->request()->user()->saveToSession();
            $this->redirect(isset($_GET['return-to']) ? $_GET['return-to'] : '/');
        } catch (InvalidCredentialsException $e) {
            $f = new Flash();
            $f->error('Username or password does not match.');
            $this->log()->log(sprintf('Unsuccessful login attempt for \'%s\'.', $_POST['User']));
            $this->redirect('/login', isset($_GET['return-to']) ? ['return-to' => $_GET['return-to']] : null);
            $this->exit();
        }
    }
}
