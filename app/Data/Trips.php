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

        return $stmt->fetchAll();
    }
}