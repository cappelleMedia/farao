<?php

//require_once dirname(__FILE__) . '/daoobject.php';
require_once dirname(__FILE__) . '/../errorhandling/domainexception.php';

class Promo implements DaoObject, JsonSerializable {

    const TIMEZONE = 'Europe/Brussels';

    private $_id = -1;
    private $_title;
    private $_text; // is description
    private $_start;
    private $_end;
    private $_active;

    /*
      $date = strtotime('21-10-1989');
      echo $date;
      echo '\n';
      echo date('d/m/Y', 624956400);
     */

    public function __construct($title, $text, $dateFormat, $start, $end) {
        $this->init();
        $this->setTitle($title);
        $this->setText($text);
        $this->setStart($dateFormat, $start);
        $this->setEnd($dateFormat, $end);
    }

    private function init() {
        date_default_timezone_set(Promo::TIMEZONE);
        $this->_active = true;
    }

    public function setActive($active) {
        $this->_active = $active;
    }

    public function triggerActive() {
        $this->setActive(!$this->_active);
    }

    public function isActive() {
        return $this->_active;
    }

    public function setTitle($title) {
        if (!(trim($title))) {
            throw new DomainModelException('Title is a required field', NULL);
        }
        $this->_title = $title;
    }

    public function setText($text) {
        if (!trim($text)) {
            throw new DomainModelException('Body is a required field', NULL);
        }
        $this->_text = $text;
    }

    public function setStart($format, $start) {
        //FIXEM should time be validated? or trust input?
        //input should be dd-mm-yyyy format
        $date = DateTime::createFromFormat($format, $start);
        $this->_start = $date;
    }

    public function setEnd($format, $end) {
        //FIXEM should time be validated? or trust input?
        //input should be dd-mm-yyyy format
        $date = DateTime::createFromFormat($format, $end);
        $this->_end = $date;
    }

    public function getTitle() {
        return $this->_title;
    }

    public function getText() {
        return $this->_text;
    }

    public function getStart() {
        return $this->_start;
    }

    public function getStartStr($format) {
        return $this->getStart()->format($format);
    }

    public function getEnd() {
        return $this->_end;
    }

    public function getEndStr($format) {
        return $this->getEnd()->format($format);
    }

    public function getId() {
        return $this->_id;
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

    public function update($title, $text, $format, $start, $end, $active) {
        $this->setTitle($title);
        $this->setText($text);
        $this->setStart($format, $start);
        $this->setEnd($format, $end);
        $this->setActive($active);
    }

    public function jsonSerialize() {
        $arr = array();
        $arr['promo_id'] = $this->getId();
        $arr['promo_title'] = $this->getTitle();
        $arr['promo_text'] = $this->getText();
        $arr['promo_start'] = $this->getStartStr('d/m/Y');
        $arr['promo_end'] = $this->getEndStr('d/m/Y');
        $arr['promo_active'] = $this->isActive();
        return $arr;
    }
}
