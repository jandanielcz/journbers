<?php


namespace Journbers\Data;


use Journbers\Controller\Exception\SanitizationException;
use Journbers\Data;
use Tracy\Debugger;

class Trips extends Data
{

    public function loadTrips($car)
    {

        $stmt = $this->database()->prepare("
            select 
              valid_trips.*
            from valid_trips
            where 
              car = :car
              and
              datediff(now(), start_date) <= 60
            order by start_odometer desc
        ");
        $stmt->execute([
            'car' => $car
        ]);

        return $this->processLoadTrips($stmt->fetchAll());
    }

    public function loadOneTrip($id)
    {
        $stmt = $this->database()->prepare("
            select 
              valid_trips.*
            from valid_trips
            where 
              id = :id
        ");
        $stmt->execute([
            'id' => $id
        ]);

        $tempArray = $this->processLoadTrips($stmt->fetchAll());

        return $tempArray[0];
    }

    protected function processLoadTrips($rows)
    {

        for ($i = 0; $i < count($rows); $i ++) {
            foreach ([ 'start_date', 'end_date', 'added_on' ] as $k) {
                $rows[$i][$k] = new \DateTimeImmutable($rows[$i][$k]);
            }

            foreach ([ 'id', 'trip_length', 'start_odometer', 'end_odometer' ] as $k) {
                $rows[$i][$k] = intval($rows[$i][$k]);
            }

            foreach ([ 'is_personal', 'and_back', 'is_locked' ] as $k) {
                $rows[$i][$k] = ( $rows[$i][$k] === '1' );
            }
        }

        return $rows;
    }

    public function addTrip($v, $currentUser)
    {
        if ($this->isOdometerValueAlreadyLocked($v['OdometerStart'])) {
            throw new SanitizationException('That low odometer values are already locked and cannot be used.');
        }

        $stmt = $this->database()->prepare("
            insert into trips values (
                null,                 -- id
                null,                 -- overwriten by
                :car,               -- car
                :driver,                -- driver
                                  -- added     
                :currentUser,            
                now(),                
                                        -- removed
                null,
                null,
                                        -- start
                :startOdometer,
                :startPlace,
                :startDate,
                                        -- target
                :targetClient, 					 -- client
                :targetPlace,              -- place
                                        -- end
                :endOdometer,
                :endPlace,
                :endDate,
                
                :isPersonal,                -- is personal
                :andBack,                  -- and back
                :note
            )
        ");

        $stmt->execute([
            'car'           => $v['Car'],
            'driver'        => $v['Driver'],
            'currentUser'   => $currentUser,
            'startOdometer' => $v['OdometerStart'],
            'startPlace'    => $v['PlaceStart'],
            'startDate'     => $v['TimeStart']->format('Y-m-d H:i:s'),
            'targetClient'  => $v['Client'],
            'targetPlace'   => $v['PlaceTarget'],
            'endOdometer'   => $v['OdometerEnd'],
            'endPlace'      => $v['PlaceEnd'],
            'endDate'       => ( $v['TimeEnd'] ) ? $v['TimeEnd']->format('Y-m-d H:i:s') : null,
            'isPersonal'    => ( $v['Personal'] ) ? 1 : 0,
            'andBack'       => ( $v['AndBack'] ) ? 1 : 0,
            'note'          => $v['Note'],
        ]);


        return $this->database()->lastInsertId();
    }

    public function editTrip($v, $currentUser)
    {
        if ($this->isOdometerValueAlreadyLocked($v['OdometerStart'])) {
            throw new SanitizationException('That low odometer values are already locked and cannot be used.');
        }

        try {
            $this->database()->beginTransaction();

            $stmt = $this->database()->prepare("
            insert into trips values (
                null,                 -- id
                null,                 -- overwriten by
                :car,               -- car
                :driver,                -- driver
                                  -- added     
                :currentUser,            
                now(),                
                                        -- removed
                null,
                null,
                                        -- start
                :startOdometer,
                :startPlace,
                :startDate,
                                        -- target
                :targetClient, 					 -- client
                :targetPlace,              -- place
                                        -- end
                :endOdometer,
                :endPlace,
                :endDate,
                
                :isPersonal,                -- is personal
                :andBack,                  -- and back
                :note
              )
            ");

            $stmt->execute([
                'car'           => $v['Car'],
                'driver'        => $v['Driver'],
                'currentUser'   => $currentUser,
                'startOdometer' => $v['OdometerStart'],
                'startPlace'    => $v['PlaceStart'],
                'startDate'     => $v['TimeStart']->format('Y-m-d H:i:s'),
                'targetClient'  => $v['Client'],
                'targetPlace'   => $v['PlaceTarget'],
                'endOdometer'   => $v['OdometerEnd'],
                'endPlace'      => $v['PlaceEnd'],
                'endDate'       => $v['TimeEnd']->format('Y-m-d H:i:s'),
                'isPersonal'    => ( $v['Personal'] ) ? 1 : 0,
                'andBack'       => ( $v['AndBack'] ) ? 1 : 0,
                'note'          => $v['Note'],
            ]);

            $newId = $this->database()->lastInsertId();

            $s2 = $this->database()->prepare("
            update trips set overwriten_by = :newId where id = :oldId
        ");
            $s2->execute([
                'newId' => $newId,
                'oldId' => $v['Id']
            ]);

            $this->database()->commit();

            return $newId;

        } catch (\Exception $e) {
            $this->database()->rollBack();
            throw $e;
        }
    }

    public function removeTrip($id, $currentUser)
    {
        $tripToRemove = $this->loadOneTrip($id);
        if ($tripToRemove['is_locked']) {
            throw new SanitizationException('Trip is locked, and non removable.');
        }


        $s2 = $this->database()->prepare("
            update trips set removed_on = now(), removed_by = :user where id = :id
        ");
        $s2->execute([
            'id'   => $id,
            'user' => $currentUser
        ]);
    }

    public function changeStartOdometer($id, $odometerStart, $currentUser)
    {

        if ($this->isOdometerValueAlreadyLocked($odometerStart)) {
            throw new SanitizationException('That low odometer values are already locked and cannot be used.');
        }

        try {
            $this->database()->beginTransaction();

            $stmt = $this->database()->prepare("
        INSERT INTO trips (
            car, driver, added_by, added_on, 
            start_odometer, start_place, start_date,
            target_client, target_place,
            end_odometer, end_place, end_date,
            is_personal, and_back, note
        ) SELECT
           car, driver, :user, NOW(),
           :startOdometer, start_place, start_date,
           target_client, target_place,
           end_odometer, end_place, end_date,
            is_personal, and_back, note
            FROM trips
            WHERE id = :id;
        ");

            $stmt->execute([
                'startOdometer' => $odometerStart,
                'id'            => $id,
                'user'          => $currentUser
            ]);

            $newId = $this->database()->lastInsertId();

            $s2 = $this->database()->prepare("
            update trips set overwriten_by = :newId where id = :oldId
        ");
            $s2->execute([
                'newId' => $newId,
                'oldId' => $id
            ]);

            $this->database()->commit();

            return $newId;

        } catch (\Exception $e) {
            $this->database()->rollBack();
            throw  $e;
        }
    }

    public function changeEndOdometer($id, $odometerEnd, $currentUser)
    {
        if ($this->isOdometerValueAlreadyLocked($odometerEnd)) {
            throw new SanitizationException('That low odometer values are already locked and cannot be used.');
        }

        try {
            $this->database()->beginTransaction();

            $stmt = $this->database()->prepare("
        INSERT INTO trips (
            car, driver, added_by, added_on, 
            start_odometer, start_place, start_date,
            target_client, target_place,
            end_odometer, end_place, end_date,
            is_personal, and_back, note
        ) SELECT
           car, driver, :user, NOW(),
           start_odometer, start_place, start_date,
           target_client, target_place,
           :endOdometer, end_place, end_date,
            is_personal, and_back, note
            FROM trips
            WHERE id = :id;
        ");

            $stmt->execute([
                'endOdometer' => $odometerEnd,
                'id'          => $id,
                'user'        => $currentUser
            ]);

            $newId = $this->database()->lastInsertId();

            $s2 = $this->database()->prepare("
            update trips set overwriten_by = :newId where id = :oldId
        ");
            $s2->execute([
                'newId' => $newId,
                'oldId' => $id
            ]);

            $this->database()->commit();

            return $newId;
        } catch (\Exception $e) {
            $this->database()->rollBack();
            throw $e;
        }
    }

    public function loadTripLockOdoValue()
    {
        $stmt = $this->database()->prepare("
            SELECT
                config_int
            FROM
            runtime_config
            WHERE config_key = 'lockTripsOdoLessThan'
        ");

        $stmt->execute();

        $res = $stmt->fetch(\PDO::FETCH_NUM)[0];
        return intval($res);
    }

    public function saveTripLockOdoValue($value, $currentUser)
    {
        $stmt = $this->database()->prepare("
            update runtime_config set 
              config_int = :val, 
              modified_by = :user, 
              modified_on = now()
            WHERE config_key = 'lockTripsOdoLessThan'
        ");

        $stmt->execute([
            'val' => $value,
            'user' => $currentUser
        ]);
    }

    protected function isOdometerValueAlreadyLocked($value)
    {
        $stmt = $this->database()->prepare("
            SELECT
                if (:v <= config_int, TRUE, FALSE) AS locked
            FROM
            runtime_config
            WHERE config_key = 'lockTripsOdoLessThan'
        ");

        $stmt->execute([
            'v' => $value
        ]);

        $res = $stmt->fetch(\PDO::FETCH_NUM)[0];
        return ($res === "1");

    }
}
