<?php


namespace Journbers\Controller;


use Journbers\Controller;
use Journbers\Data\Trips;
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

        if ($this->request->segment(0) === null) {
            $this->redirect(sprintf('/%s/', $this->config()->get('hardcodedCar')));
        }
    }

    public function trips()
    {
        if (!$this->request()->user()->hasRole('driver')) {
            $this->redirect('/login');
            $this->exit();
        }

        $trips = new Trips([
            'host' => $this->config->get('DB_SERVER'),
            'port' => $this->config->get('DB_PORT'),
            'dbname' => $this->config->get('DB_NAME'),
            'user' => $this->config->get('DB_USER'),
            'password' => $this->config->get('DB_PASS')
        ]);


        $t = new Template('home');
        $t->display([
            'f' => new Flash(),
            'trips' => $trips->loadTrips($this->request()->segment(0)),
            'currentUser' => $this->request()->user()->getId(),
            'car' => $this->config()->get('hardcodedCar'),
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
        if (!$this->request()->user()->hasRole('driver')) {
            $this->redirect('/login');
            $this->exit();
        }

        $f = new Flash();

        $t = new Template('add');
        $t->display([
            'f' => $f,
            'car' => $this->config()->get('hardcodedCar'),
            'prefill' => $f->getPayload('AddPrefill'),
            'driver' => $this->request()->user()->getId()
        ]);
    }
}