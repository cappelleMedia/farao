<?php

//require_once 'beersdao.php';
//require_once dirname(__FILE__) . '/../../model/beer.php';

class BeersMemDB implements BeersDao {

    private $_beers;

    public function __construct() {
        $this->init();
    }

    private function init() {
        $this->_beers = array();
        try{
        $this->tmpFill();
        } catch(DomainModelException $ex){
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    function tmpFill() {
        $bNameCorrect = 'Jupiler';
        $bBrewNameCorrect = 'Ab-inbev';
        $bBrewUrlCorrect = 'http://jupiler.be/';
        $bAvailBool = true;
        $bTypeCorrect = 'fles';
        $bDegCorrectComma = 5.2;
        $beer = new Beer($bNameCorrect, $bDegCorrectComma, $bBrewNameCorrect, $bBrewUrlCorrect, $bAvailBool, $bTypeCorrect);
        $this->add($beer);
        $beer2 = new Beer($bNameCorrect, $bDegCorrectComma, $bBrewNameCorrect, $bBrewUrlCorrect, $bAvailBool, 'vat');
        $this->add($beer2);
        $beer3 = new Beer($bNameCorrect, $bDegCorrectComma, $bBrewNameCorrect, $bBrewUrlCorrect, $bAvailBool, 'trappist');
        $this->add($beer3);
        $beer4 = new Beer('niet beschikbaar', $bDegCorrectComma, $bBrewNameCorrect, $bBrewUrlCorrect, false, 'fles');
        $this->add($beer4);
    }

    private function assignId($beer) {
        $id = 0;
        if(!empty($this->_beers)){
            $id = count($this->_beers);
        }
        try {
            $beer->setId($id);
            return $beer;
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    public function add($beer) {
        if (!$beer instanceof Beer) {
            throw new DBException('The object you tried to add was not a beer object', NULL);
        }
        if ($this->containsId($beer->getId())) {
            throw new DBException('The database already contains a beer with this id', NULL);
        }
        if ($this->containsNameSameType($beer->getName(), $beer->getType())) {
            throw new DBException('It seems you have already added a beer for this name-type combination', NULL);
        }
        if ($beer->getId() < 0) {
            $this->assignId($beer); //Should the action preformed her rethrow?(java would use 'throws' in method dec
        }
        $this->_beers[$beer->getId()] = $beer;
    }

    public function containsNameSameType($beerName, $beerType) {
        if (!empty($beerName) && !empty($beerType)) {
            if ($this->getFromNameAndType($beerName, $beerType)) {
                return true;
            }
        }
        return false;
    }

    public function containsId($id) {
        if (isset($this->_beers) && !empty($this->_beers)) {
            return array_key_exists($id, $this->_beers);
        }
        return false;
    }

    private function containsName($identifier) {
        if (!isset($this->_beers) || empty($this->_beers)) {
            return false;
        }
        foreach ($this->_beers as $key => $b) {
            if ($b instanceof Beer && $b->getName() === $identifier) {
                return true;
            }
        }
        return false;
    }

    private function getFromNameAndType($bName, $bType) {
        if (!$this->containsName($bName)) {
            return 0;
        }
        $beer;
        foreach ($this->_beers as $key => $b) {
            if ($b->getName() === $bName && $b->getType() === $bType) {
                $beer = $b;
            }
        }
        if (isset($beer) && $beer instanceof Beer) {
            return $beer;
        }
        return NULL;
    }

    public function get($id) {
        if (!$this->containsId($id)) {
            throw new DBException('could not find a beer with this id. id was: ' . $id, NULL);
        }
        return $this->_beers[$id];
    }

    public function getByString($identifier) {
        $beersWithName = array();
        foreach ($this->_beers as $key => $beer) {
            if ($beer->getName() === $identifier) {
                $beersWithName[$key] = $beer;
            }
        }
        return $beersWithName;
    }

    public function getBeersAll($admin) {
        return $this->_beers;
    }

    public function getBeersBottle($admin) {
        $beersBottle = array();
        foreach ($this->_beers as $key => $beer) {
            if ($beer->getType() === 'fles') {
                $beersBottle[$key] = $beer;
            }
        }
        return $beersBottle;
    }

    public function getBeersTap($admin) {
        $beersBottle = array();
        foreach ($this->_beers as $key => $beer) {
            if ($beer->getType() === 'vat') {
                $beersBottle[$key] = $beer;
            }
        }
        return $beersBottle;
    }

    public function getBeersTrappist($admin) {
        $beersBottle = array();
        foreach ($this->_beers as $key => $beer) {
            if ($beer->getType() === 'trappist') {
                $beersBottle[$key] = $beer;
            }
        }
        return $beersBottle;
    }

    public function remove($id) {
        if (!$this->containsId($id)) {
            throw new DBException('No beer with this id was found', NULL);
        }
        unset($this->_beers[$id]);
    }

    public function setAvailable($id, $available) {
        if (!$this->containsId($id)) {
            throw new DBException('No beer with this id was found', NULL);
        }
        try {
            $this->_beers[$id]->setAvailable($available);
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    public function updateBeer($id, $name, $degrees, $brewery_name, $brewery_url, $available, $type) {
        if (!$this->containsId($id)) {
            throw new DBException('No beer with this id was found', NULL);
        }
        $this->_beers[$id]->update($name, $degrees, $brewery_name, $brewery_url, $available, $type);
    }

}
