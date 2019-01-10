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

        $t = new Template('home');
        $t->display([
            'f' => new Flash()
        ]);
    }

    public function login()
    {
        $t = new Template('login');
        $t->display([
            'f' => new Flash()
        ]);
    }

    public function add()
    {
        $t = new Template('add');
        $t->display([
            'f' => new Flash()
        ]);
    }
}