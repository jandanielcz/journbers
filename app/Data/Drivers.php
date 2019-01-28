<?php


namespace Journbers\Data;


use Journbers\Data;

class Drivers extends Data
{
    public function loadDrivers()
    {
        $stmt = $this->database()->prepare("
            select * from users order by fullname
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}