<?php


namespace Journbers\Controller;


use Journbers\Controller;
use Journbers\Flash;
use Journbers\Template;
use Tracy\Debugger;
use Journbers\Controller\Exception\SanitizationException;

class Entry extends Controller
{

    protected function sanitizeAddPayload()
    {
        $payload = [];
        $payload['Car'] = $_POST['Car'];
        if (empty($payload['Car'])) {
            throw new SanitizationException('Car should not be emtpy.');
        }

        if (empty($_POST['OdometerStart'])) {
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

    public function beforeAdd()
    {
        Debugger::dump($_POST);

        // sanitize post data
        try {
            $addPayload = $this->sanitizeAddPayload();
            Debugger::dump($addPayload);
        } catch (SanitizationException $e) {
            $f = new Flash();
            $f->error($e->getMessage());
            $f->addPayload('AddPrefill', $_POST);
            $this->redirect('/add');
        }




        // if any reason, redirect to /add with pre-fill payload
        // request add payload
    }

    public function add()
    {
        // TODO: add role requirement

        Debugger::dump($this->request());
    }
}