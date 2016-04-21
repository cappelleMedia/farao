<?php

class MenuItem {

    private $_action;
    private $_description;
    private $_pageName;

    public function __construct($action, $description, $pageName) {
        $this->setAction($action);
        $this->setDescription($description);
        $this->setPageName($pageName);
    }

    public function getAction() {
        return $this->_action;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function getPageName() {
        return $this->_pageName;
    }

    private function setAction($action) {
        $this->_action = $action;
    }

    private function setDescription($description) {
        $this->_description = $description;
    }

    private function setPageName($pageName) {
        $this->_pageName = $pageName;
    }

}
