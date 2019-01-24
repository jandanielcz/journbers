<?php


namespace Journbers\Controller;


use Journbers\Controller;
use Journbers\Data;
use Journbers\Data\Trips;
use Journbers\Flash;

class Page extends Controller
{
    public function index()
    {
        if ($this->request->segment(0) === null) {
            $this->redirect(sprintf('/%s/', $this->config()->get('hardcodedCar')));
        }
    }

    public function trips()
    {
        $trips = new Trips($this->connectionParams());

        $this->template('home')->display([
            'f' => new Flash(),
            'trips' => $trips->loadTrips($this->request()->segment(0)),
            'currentUser' => $this->request()->user()->id(),
            'car' => $this->config()->get('hardcodedCar'),
        ]);
    }

    public function lock()
    {

        $d = new Trips($this->connectionParams());
        $lockValue = $d->loadTripLockOdoValue();

        $this->template('lock')->display([
            'f' => new Flash(),
            'car' => $this->config()->get('hardcodedCar'),
            'lockValue' => $lockValue,
        ]);
    }

    public function login()
    {
        $this->template('login')->display([
            'f' => new Flash()
        ]);
    }

    public function add()
    {
        $f = new Flash();

        $this->template('add')->display([
            'f' => $f,
            'car' => $this->config()->get('hardcodedCar'),
            'prefill' => $f->getPayload('AddPrefill'),
            'driver' => $this->request()->user()->id()
        ]);
    }

    public function fillSpace()
    {
        $f = new Flash();
        $prefil = $this->sanitizeFillSpaceInput();
        $f->addPayload('AddPrefill', $prefil);

        $this->redirect(sprintf('/%s/add', $this->config()->get('hardcodedCar')));
        $this->exit();
    }

    public function edit()
    {
        $trips = new Trips($this->connectionParams());
        $trip = $trips->loadOneTrip($this->request()->segment(1));

        $f = new Flash();

        $payload = $this->tripToFormData($trip);

        $this->template('add')->display([
            'f' => $f,
            'car' => $this->config()->get('hardcodedCar'),
            'prefill' => $payload,
            'driver' => $this->request()->user()->id()
        ]);
    }

    protected function sanitizeFillSpaceInput()
    {
        $o = [
            'OdometerStart' => intval($_POST['SpaceStart']),
            'OdometerEnd' => intval($_POST['SpaceEnd']),
            'TimeStart' => (new \DateTimeImmutable($_POST['SpaceMinTime']))->format('Y-m-d\TH:i'),
            'TimeEnd' => (new \DateTimeImmutable($_POST['SpaceMaxTime']))->format('Y-m-d\TH:i')
        ];

        return $o;
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
