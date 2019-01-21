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

        $trips = new Trips($this->connectionParams());


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

    public function edit()
    {
        if (!$this->request()->user()->hasRole('driver')) {
            $this->redirect('/login');
            $this->exit();
        }

        $trips = new Trips($this->connectionParams());
        $trip = $trips->loadOneTrip($this->request()->segment(1));

        $f = new Flash();

        $payload = $this->tripToFormData($trip);

        Debugger::barDump($trip);

        $t = new Template('add');
        $t->display([
            'f' => $f,
            'car' => $this->config()->get('hardcodedCar'),
            'prefill' => $payload,
            'driver' => $this->request()->user()->getId()
        ]);
    }

    protected function tripToFormData($trip)
    {
        $o = [];

        $o['Id'] = $trip['id'];
        $o['Car'] = $trip['car'];
        $o['Driver'] = $trip['driver'];

        $o['OdometerStart'] = $trip['start_odometer'];
        $o['PlaceStart'] = $trip['start_place'];
        $o['TimeStart'] = $trip['start_date']->format('Y-m-d\TH:i');

        $o['OdometerEnd'] = $trip['end_odometer'];
        $o['PlaceEnd'] = $trip['end_place'];
        $o['TimeEnd'] = $trip['end_date']->format('Y-m-d\TH:i');

        $o['Client'] = $trip['target_client'];
        $o['PlaceTarget'] = $trip['target_place'];

        $o['Personal'] = ($trip['is_personal']) ? 1 : 0;
        $o['AndBack'] = ($trip['and_back']) ? 1 : 0;

        $o['Note'] = $trip['note'];

        return $o;
    }
}