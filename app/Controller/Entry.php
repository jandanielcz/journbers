<?php


namespace Journbers\Controller;


use Journbers\Controller;
use Journbers\Data\Trips;
use Journbers\Flash;
use Tracy\Debugger;
use Journbers\Controller\Exception\SanitizationException;

class Entry extends Controller
{

    private $addPayload = null;
    private $editPayload = null;


    public function sanitizeEditPayload()
    {
        $payload = $this->sanitizeAddPayload();
        $payload['Id'] = intval($_POST['Id']);

        return $payload;
    }

    public function beforeAdd()
    {
        try {
            $this->addPayload = $this->sanitizeAddPayload();
        } catch (SanitizationException $e) {
            $f = new Flash();
            $f->error($e->getMessage());
            $f->addPayload('AddPrefill', $_POST);
            $car = (!empty($this->addPayload['Car'])) ? $this->addPayload['Car'] : $this->config()->get('hardcodedCar');
            $this->redirect(sprintf('/%s/add', $car));
            $this->exit();
        }
    }

    public function add()
    {
        $trips = new Trips($this->connectionParams());

        try {
            $newId = $trips->addTrip($this->addPayload, $this->request()->user()->id());
            $this->redirect(sprintf('/%s/?highlight=%s', $this->addPayload['Car'], $newId));
            $this->exit();
        } catch (\Exception $e) {
            $f = new Flash();
            $f->error($e->getMessage());
            $f->addPayload('AddPrefill', $_POST);
            $car = (!empty($this->addPayload['Car'])) ? $this->addPayload['Car'] : $this->config()->get('hardcodedCar');
            $this->redirect(sprintf('/%s/add', $car));
            $this->exit();
        }
    }

    public function beforeEdit()
    {
        try {
            $this->editPayload = $this->sanitizeEditPayload();
        } catch (SanitizationException $e) {
            $f = new Flash();
            $f->error($e->getMessage());
            $f->addPayload('AddPrefill', $_POST);
            $this->redirect(sprintf('/edit/%s', $this->editPayload['Id']));
            $this->exit();
        }
    }

    public function edit()
    {

        $trips = new Trips($this->connectionParams());

        try {
            Debugger::barDump($this->editPayload);
            $newId = $trips->editTrip($this->editPayload, $this->request()->user()->id());
            $this->redirect(sprintf('/%s/?highlight=%s', $this->editPayload['Car'], $newId));
            $this->exit();
        } catch (\Exception $e) {
            $f = new Flash();
            $f->error($e->getMessage());
            $f->addPayload('AddPrefill', $_POST);
            $this->redirect(sprintf('/edit/%s/', $this->editPayload['Id']));
            $this->exit();
        }
    }

    public function remove()
    {

        $trips = new Trips($this->connectionParams());
        $id = $this->request()->segment(1);

        try {
            $trips->removeTrip($id, $this->request()->user()->id());
            // TODO: Doesnt support multiple Cars
            $this->redirect(sprintf('/'));
            $this->exit();
        } catch (\Exception $e) {
            $f = new Flash();
            $f->error($e->getMessage());
            // TODO: Doesnt support multiple Cars
            $this->redirect(sprintf('/'));
            $this->exit();
        }
    }

    public function spaceToStart()
    {

        $trips = new Trips($this->connectionParams());
        // TODO: Sanitization?
        try {
            $newId = $trips->changeStartOdometer(
                intval($_POST['TripId']),
                intval($_POST['SpaceStart']),
                $this->request()->user()->id()
            );
            $this->redirect(sprintf('/%s/?highlight=%s', $this->config()->get('hardcodedCar'), $newId));
            $this->exit();
        } catch (\Exception $e) {
            $f = new Flash();
            $f->error($e->getMessage());
            $this->redirect(sprintf('/%s/', $this->config()->get('hardcodedCar')));
            $this->exit();
        }
    }

    public function spaceToEnd()
    {

        $trips = new Trips($this->connectionParams());
        // TODO: Sanitization?
        try {
            $newId = $trips->changeEndOdometer(
                intval($_POST['TripId']),
                intval($_POST['SpaceEnd']),
                $this->request()->user()->id()
            );
            $this->redirect(sprintf('/%s/?highlight=%s', $this->config()->get('hardcodedCar'), $newId));
            $this->exit();
        } catch (\Exception $e) {
            $f = new Flash();
            $f->error($e->getMessage());
            $this->redirect(sprintf('/%s/', $this->config()->get('hardcodedCar')));
            $this->exit();
        }
    }

    protected function sanitizeAddPayload()
    {
        $payload = [];
        $payload['Car'] = $_POST['Car'];
        if (empty($payload['Car'])) {
            throw new SanitizationException('Car should not be emtpy.');
        }

        if ($_POST['OdometerStart'] === '') {
            throw new SanitizationException('Odometer start value should be defined.');
        }
        $payload['OdometerStart'] = intval($_POST['OdometerStart']);

        if (empty($_POST['TimeStart'])) {
            throw new SanitizationException('Star date and time should be defined.');
        }
        try {
            $payload['TimeStart'] = new \DateTimeImmutable($_POST['TimeStart']);
        } catch (\Exception $e) {
            throw new SanitizationException('Cannot parse trip start date and time.');
        }

        if (empty($_POST['PlaceStart'])) {
            throw new SanitizationException('Start place should be defined.');
        }

        $payload['PlaceStart'] = $_POST['PlaceStart'];
        $payload['Driver'] = $_POST['Driver'];
        $payload['Personal'] = ($_POST['Personal'] == '1');
        $payload['Client'] = ($_POST['Client'] == '') ? null : $_POST['Client'];
        $payload['PlaceTarget'] = ($_POST['PlaceTarget'] == '') ? null : $_POST['PlaceTarget'];
        $payload['AndBack'] = ($_POST['AndBack'] == '1');

        if ($_POST['OdometerEnd'] === '') {
            $payload['OdometerEnd'] = null;
        } else {
            $payload['OdometerEnd'] = intval($_POST['OdometerEnd']);
        }

        if ($_POST['TimeEnd'] === '') {
            $payload['TimeEnd'] = null;
        } else {
            try {
                $payload['TimeEnd'] = new \DateTimeImmutable($_POST['TimeEnd']);
            } catch (\Exception $e) {
                throw new SanitizationException('Cannot parse trip end date and time.');
            }
        }

        $payload['PlaceEnd'] = ($_POST['PlaceEnd'] == '') ? null : $_POST['PlaceEnd'];
        $payload['Note'] = ($_POST['Note'] == '') ? null : $_POST['Note'];

        return $payload;
    }
}
