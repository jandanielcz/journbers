<?php


namespace Journbers\Controller;


use Journbers\Controller;
use Journbers\Template;

class Page extends Controller
{
    public function index()
    {
        if (true) {
            $this->redirect('/login');
            $this->exit();
        }
    }

    public function login()
    {
        $t = new Template('login');
        $t->display();
    }
}