<?php

//require_once dirname(__FILE__) . '/daoobject.php';
//require_once dirname(__FILE__) . '/../errorhandling/domainexception.php';

class GuestPost implements DaoObject {

    private $_timeZone = 'Europe/Brussels';
    private $_id = -1;
    private $_name;
    private $_body;
    private $_dateTime; //FIXME refactor to datetime::

    public function __construct($name, $body, $date) {
        $this->init();
        $this->setName($name);
        $this->setBody($body);
        $this->setDateTime($date);
    }

    private function init() {
        date_default_timezone_set($this->_timeZone);
    }

    public function setId($id = -1) {
        if (!is_numeric($id)) {
            throw new DomainModelException('Id must be a numeric value but was: ' . $id, NULL);
        }
        if ($id < 0) {
            throw new DomainModelException('Id can\'t be less than zero', NULL);
        }
        $this->_id = $id;
    }

    public function setName($name) {
        if (!(trim($name))) {
            $this->_name = 'Anoniem'; //FIXME should this be required?
        } else {
            $this->_name = $name;
        }
    }

    public function setBody($body) {
        if (!(trim($body))) {
            throw new DomainModelException('Body is a required field', NULL);
        }
        $this->_body = $body;
    }

    public function setDateTime($dateTime) {
        if ($dateTime === NULL) {
            $dateTime = time();
        }
        $this->_dateTime = $dateTime;
    }

    public function getName() {
        return $this->_name;
    }

    public function getBody() {
        return $this->_body;
    }

    public function getDateTime() {
        return $this->_dateTime;
    }

    public function getDateTimeStr() {
        $day = date('j', $this->getDateTime());
        $month = $this->getMonthString(date('m', $this->getDateTime()));
        $year = date('Y', $this->getDateTime());
        $hour = date('H', $this->getDateTime());
        $min = date('i', $this->getDateTime());
//        return date('d/m/Y H:i', $this->getDateTime());
        return $day . ' ' . $month . ' ' . $year . ' om ' . $hour .':'.$min;
    }

    private function getMonthString($month) {
        switch ($month) {
            case '01':
                return 'januari';
            case '02':
                return 'februari';
            case '03':
                return 'maart';
            case '04':
                return 'april';
            case '05':
                return 'mei';
            case '06':
                return 'juni';
            case '07':
                return 'juli';
            case '08':
                return 'augustus';
            case '09':
                return 'september';
            case '10':
                return 'oktober';
            case '11':
                return 'november';
            case '12':
                return 'december';
            default :
                return 'n/a';
        }
    }

    public function getId() {
        return $this->_id;
    }
}
