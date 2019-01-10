<?php


namespace Journbers\Controller;


use Journbers\Controller;
use Journbers\Flash;
use Journbers\Template;
use Tracy\Debugger;

class Page extends Controller
{
    public function index()
    {
        if (!$this->request()->user()->hasRole('driver')) {
            $this->redirect('/login');
            $this->exit();
        }
        Debugger::barDump($_SESSION);
        echo 'TADY';
    }

    public function login()
    {
        $t = new Template('login');
        $t->display([
            'f' => new Flash()
        ]);
    }
}