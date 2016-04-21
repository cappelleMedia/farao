<?php

class Lineup implements DaoObject, JsonSerializable {

    private $_id = -1;
    private $_name, $_web, $_pic;  //Strings 

    public function __construct($name, $web, $pic) {
        $this->setName($name);
        $this->setWeb($web);
        $this->setPic($pic);
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
        $this->_name = $name;
    }

    public function setWeb($web) {
        $this->_web = $web;
    }

    public function setPic($pic) {
        $this->_pic = $pic;
    }

    public function getName() {
        return $this->_name;
    }
    
    public function getWeb() {
        return $this->_web;
    }
    
    public function getPic() {
        return $this->_pic;
    }
    
    public function getId() {
        return $this->_id;
    }

    public function jsonSerialize() {
        $arr = array();
        $arr['lineup_id'] = $this->getId();
        $arr['lineup_name'] = $this->getName();
        $arr['lineup_web'] = $this->getWeb();
        $arr['lineup_pic'] = $this->getPic();
        return $arr;
    }

}
