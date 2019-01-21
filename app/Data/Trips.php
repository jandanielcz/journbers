<?php


namespace Journbers\Data;


use Journbers\Data;

class Trips extends Data
{

    public function loadTrips($car)
    {
        $stmt = $this->db()->prepare("
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
        $stmt = $this->db()->prepare("
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

        for ($i = 0; $i < count($rows); $i++) {

            foreach (['start_date', 'end_date'] as $k) {
                $rows[$i][$k] = new \DateTimeImmutable($rows[$i][$k]);
            }

            foreach (['id', 'trip_length', 'start_odometer', 'end_odometer'] as $k) {
                $rows[$i][$k] = intval($rows[$i][$k]);
            }

            foreach (['is_personal', 'and_back'] as $k) {
                $rows[$i][$k] = ($rows[$i][$k] === '1');
            }
        }

        return $rows;
    }

    public function addTrip($v, $currentUser)
    {
        $stmt = $this->db()->prepare("
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
            'car' => $v['Car'],
            'driver' => $v['Driver'],
            'currentUser' => $currentUser,
            'startOdometer' => $v['OdometerStart'],
            'startPlace' => $v['PlaceStart'],
            'startDate' => $v['TimeStart']->format('Y-m-d H:i:s'),
            'targetClient' => $v['Client'],
            'targetPlace' => $v['PlaceTarget'],
            'endOdometer' => $v['OdometerEnd'],
            'endPlace' => $v['PlaceEnd'],
            'endDate' => $v['TimeEnd']->format('Y-m-d H:i:s'),
            'isPersonal' => ($v['Personal']) ? 1 : 0,
            'andBack' => ($v['AndBack']) ? 1 : 0,
            'note' => $v['Note'],
        ]);


        return $this->db()->lastInsertId();
    }

    public function editTrip($v, $currentUser)
    {
        $stmt = $this->db()->prepare("
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
            'car' => $v['Car'],
            'driver' => $v['Driver'],
            'currentUser' => $currentUser,
            'startOdometer' => $v['OdometerStart'],
            'startPlace' => $v['PlaceStart'],
            'startDate' => $v['TimeStart']->format('Y-m-d H:i:s'),
            'targetClient' => $v['Client'],
            'targetPlace' => $v['PlaceTarget'],
            'endOdometer' => $v['OdometerEnd'],
            'endPlace' => $v['PlaceEnd'],
            'endDate' => $v['TimeEnd']->format('Y-m-d H:i:s'),
            'isPersonal' => ($v['Personal']) ? 1 : 0,
            'andBack' => ($v['AndBack']) ? 1 : 0,
            'note' => $v['Note'],
        ]);


        $newId = $this->db()->lastInsertId();

        $s2 = $this->db()->prepare("
            update trips set overwriten_by = :newId where id = :oldId
        ");
        $s2->execute([
            'newId' => $newId,
            'oldId' => $v['Id']
        ]);

        return $newId;

    }
}