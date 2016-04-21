<?php

//require_once dirname(__FILE__) . '/daoobject.php';
require_once dirname(__FILE__) . '/../errorhandling/domainexception.php';

class Event implements DaoObject, JsonSerializable {

    const TIMEZONE = 'Europe/Brussels';
    const BEFORMAT = 'd/m/Y G:i:s';
    const MYSQLFORMAT ='Y-m-d G:i:s';

    private $_id = -1;
    private $_title;
    private $_text; // is description
    private $_start; //Date AND time
    private $_pic;
    private $_lineup; // assoc array (id, lineup) | cross table in sql

    public function __construct($title, $text, $dateFormat, $start, $pic, $lineup) {
        $this->init();
        $this->setTitle($title);
        $this->setText($text);
        $this->setStart($dateFormat, $start);
        $this->setPic($pic);
        $this->setLineup($lineup);
    }

    private function init() {
        date_default_timezone_set(Event::TIMEZONE);        
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

    public function setPic($pic) {
        if (!trim($pic)) {
            throw new DomainModelException('Picture is a required field', NULL);
        }
        $this->_pic = $pic;
    }

    public function setLineup($lineup) {
        if (!$lineup || !is_array($lineup)) {
            throw new DomainModelException('Lineup is a required field', NULL);
        }
        $this->_lineup = $lineup;
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

    public function getPic() {
        return $this->_pic;
    }

    public function getLineup() {
        return $this->_lineup;
    }

//    public function getEndStr($format) {
//        return $this->getEnd()->format($format);
//    }

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

    //wont be realy used?
    public function addToLineup($newLineup) {
        if (!$newLineup) {
            throw new DomainModelException('You can\'t add an empty lineup item', NULL);
        }
        if(!($newLineup instanceof Lineup)) {
            throw new DomainModelException('Lineup was not an instance of lineup.class');
        }
        if (array_key_exists($newLineup->getId(), $this->_lineup)) {
            throw new DomainModelException('This name was already in the lineup', NULL);
        }
        $this->_lineup[$newLineup->getId()] = $newLineup;
    }

    public function removeFromLineup($lineupId) {        
        if (!in_array($lineupId, $this->_lineup)) {
            throw new DomainModelException('The lineupitem was not in the lineup. id was: ' . $lineupId, NULL);
        }
        unset($this->_lineup[$lineupId]);
    }
    
    public function getLineupItem($lineupId) {
        if (!in_array($lineupId, $this->_lineup)) {
            throw new DomainModelException('The lineupitem was not in the lineup. id was: ' . $lineupId, NULL);
        }
        return $this->_lineup[$lineupId];
    }

    public function update($title, $text, $format, $start, $pic) {
        $this->setTitle($title);
        $this->setText($text);
        $this->setStart($format, $start);
        $this->setPic($pic);        
    }

    public function jsonSerialize() {
        $arr = array();
        $arr['event_id'] = $this->getId();
        $arr['event_title'] = $this->getTitle();
        $arr['event_text'] = $this->getText();
        $arr['event_start'] = $this->getStartStr('d/m/Y');
        $arr['event_pic'] = $this->getPic();
        $arr['event_lineup'] = $this->getLineup();
        return $arr;
    }

}
