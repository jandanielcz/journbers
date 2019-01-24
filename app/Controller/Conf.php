<?php


namespace Journbers\Controller;


use Journbers\Controller;
use Journbers\Data\Trips;

class Conf extends Controller
{

    public function lock()
    {
        $newLockValue = intval($_POST['LockValue']);
        $t = new Trips($this->connectionParams());

        $t->saveTripLockOdoValue($newLockValue, $this->request()->user()->id());
        $this->redirect('/c/lock');
        $this->exit();
    }

}