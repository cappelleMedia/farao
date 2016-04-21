<?php
//require_once dirname(__FILE__) . '/../dao.php';

interface BeersDao extends Dao {

    public function getBeersAll($admin);
    public function getBeersBottle($admin);
    public function getBeersTrappist($admin);
    public function getBeersTap($admin);
    public function containsNameSameType($beerName, $beerType);
    public function updateBeer($id, $name, $degrees, $brewery_name, $brewery_url, $available, $type);
    public function setAvailable($id, $available);
}
