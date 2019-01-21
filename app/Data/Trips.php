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
}