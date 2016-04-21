<?php
//
//require_once dirname(__FILE__) . '/../validation/formvalidator.php';
//require_once dirname(__FILE__) . '/daoobject.php';
//require_once dirname(__FILE__) . '/../errorhandling/domainexception.php';

class Beer implements DaoObject {

//code 1 will stand for model errors
    private $_types = array('fles', 'vat', 'trappist');
    private $_id = -1;
    private $_name;
    private $_degrees;
    private $_brewery_name;
    private $_brewery_url;
    private $_available = true;
    private $_type;
    private $_validator;

    public function __construct($name, $degrees, $brewery_name, $brewery_url, $available, $type) {
        $this->init();
        try {
            $this->setName($name);
            $this->setDegrees($degrees);
            $this->setBrewery_name($brewery_name);
            $this->setBrewery_url($brewery_url);
            $this->setAvailable($available);
            $this->setType($type);
        } catch (Exception $ex) {
            throw new DomainModelException($ex->getMessage(), $ex);
        }
    }

    private function init() {
        $this->_validator = new FormValidator();
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
            throw new DomainModelException('Name is a required field', NULL);
        }
        $this->_name = $name;
    }

    public function setDegrees($degrees) {
        if(empty($degrees)) {
            throw new DomainModelException('Degrees can\'t be empty', NULL);
        }
        if (!is_numeric($degrees)) {
            throw new DomainModelException('Degrees should be a decimal but was: '. $degrees . ')', NULL);
        }
        if ($degrees < 0) {
            throw new DomainModelException('You can not set a negative value for degrees', NULL);
        }
        $this->_degrees = $degrees;
    }

    public function setBrewery_name($brewery_name) {
        if (empty($brewery_name)) {
            throw new DomainModelException('Brewery name is a required field', NULL);
        }
        $this->_brewery_name = $brewery_name;
    }

    public function setBrewery_url($brewery_url) {
        if (empty($brewery_url)) {
            throw new DomainModelException('Brewery url is a required field', NULL);
        }
        if (!$this->_validator->isValidURL($brewery_url)) {
            throw new DomainModelException('Make sur you filled in a working url( somthing like: www.site.be) but was: '. $brewery_url, NULL);
        }
        $this->_brewery_url = $brewery_url;
    }

    public function setAvailable($available) {
        //TODO should i check for boolean or not?
        if (!is_bool($available)) {
            throw new DomainModelException('Available should be true or false but was: ' . $available, NULL);
        }
        $this->_available = $available;
    }

    public function setType($type) {
        if (empty($type)) {
            throw new DomainModelException('Type is a required field', NULL);
        }
        if (!in_array($type, $this->_types)) {
            throw new DomainModelException('Type must be either \'fles\', \'vat\' or \'trappist\' but was: ' . $type, NULL);
        }
        $this->_type = $type;
    }

    public function update($name, $degrees, $brewery_name, $brewery_url, $available, $type) {
        try {
            $this->setName($name);
            $this->setDegrees($degrees);
            $this->setBrewery_name($brewery_name);
            $this->setBrewery_url($brewery_url);
            $this->setAvailable($available);
            $this->setType($type);
        } catch (DomainModelException $ex) {
            throw new DomainModelException($ex->getMessage(), $ex);
        }
    }

    public function getId() {
        return $this->_id;
    }

    public function getName() {
        return $this->_name;
    }

    public function getDegrees() {
        return $this->_degrees;
    }

    public function getBrewery_name() {
        return $this->_brewery_name;
    }

    public function getBrewery_url() {
        return $this->_brewery_url;
    }

    public function getAvailable() {
        return $this->_available;
    }

    public function getType() {
        return $this->_type;
    }

}
