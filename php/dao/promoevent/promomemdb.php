<?php

class PromoMemDB implements PromoDao {

    private $_promos;

    public function __construct() {
        $this->init();
    }

    private function init() {
        $this->_promos = array();
        try {
            $this->tmpFill();
        } catch (DomainException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    private function tmpFill() {
        date_default_timezone_set('Europe/Brussels');
        $date = date('d/m/Y');
        $pStart = $date;
        $pEnd = date('d/m/Y', strtotime(str_replace('/', '-', $date) . '+1 days'));
        for ($i = 0; $i < 6; $i++) {
            $promo = new Promo('promo ' . ($i + 1), 'Product nu aan slechts 3.50 euro', 'd/m/Y', $pStart, $pEnd);
            $this->add($promo);
        }
    }

    private function assignId($promo) {
        $id = 0;
        if (!empty($this->_promos)) {
            $id = count($this->_promos);
        }
        try {
            $promo->setId($id);
            return $promo;
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    public function add($promo) {
        if (!$promo instanceof Promo) {
            throw new DBException('The object you tried to add was not a promo object', NULL);
        }
        if ($this->containsId($promo->getId())) {
            throw new DBException('The database already contains a promo with this id', NULL);
        }
        if ($promo->getId() < 0) {
            $this->assignId($promo);
        }
        $this->_promos[$promo->getId()] = $promo;
    }

    public function containsId($id) {
        if (isset($this->_promos) && !empty($this->_promos)) {
            return array_key_exists($id, $this->_promos);
        }
        return false;
    }

    public function get($id) {
        if (!$this->containsId($id)) {
            throw new DBException('could not find a promo with this id. id was: ' . $id, NULL);
        }
        return $this->_promos[$id];
    }

    public function getByString($identifier) {
        foreach ($this->_promos as $promo) {
            if ($promo->getTitle() === $identifier) {
                return $promo;
            }
        }
        return null;
    }

    public function getPromos() {
        return $this->_promos;
    }

    public function getCurrentPromos() {
        /* $dateStart = DateTime::createFromFormat('d/m/Y', date('01/01/2016'));
          $dateEnd = DateTime::createFromFormat('d/m/Y', date('01/02/2016'));
          $dateBetw = DateTime::createFromFormat('d/m/Y', date('28/01/2016'));
          $dateEarlier = DateTime::createFromFormat('d/m/Y', date('01/01/2016'));
          $dateLater = DateTime::createFromFormat('d/m/Y', date('28/02/2016'));
         */

        $now = DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
        $curr = array();
        foreach ($this->_promos as $key => $currPromo) {
            if ($this->isBetween($currPromo->getStart(), $currPromo->getEnd(), $now)) {
                $curr[$key] = $currPromo;
            }
        }
        return $curr;
    }

    private function isBetween($tstart, $tend, $tcurr) {
        return ($tcurr > $tstart) && ($tcurr < $tend);
    }

    public function remove($id) {
        if (!$this->containsId($id)) {
            throw new DBException('No promo with this id was found', NULL);
        }
        unset($this->_promos[$id]);
    }

    public function updatePromo($id, $promoTitle, $promoText, $format, $promoStart, $promoEnd) {
        if (!$this->containsId($id)) {
            throw new DBException('No promo with this id was found', NULL);
        }
        $this->_promos[$id]->update($promoTitle, $promoText, $format, $promoStart, $promoEnd);
    }

}
