<?php

class App_Doctrine_Hydrator_Pairs extends Doctrine_Hydrator_Abstract
{
    public function hydrateResultSet($stmt)
    {
        $data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        // do something to with $data
        return $data;
    }
}