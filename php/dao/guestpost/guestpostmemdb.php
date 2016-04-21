<?php

//require_once 'guestpostdao.php';
//require_once dirname(__FILE__) . '/../../model/guestpost.php';

class GuestPostMemDB implements GuestPostDao {

    private $_guestPosts;

    public function __construct() {
        $this->init();
    }

    private function init() {
        $this->_guestPosts = array();
        try {
            $this->tmpFill();
        } catch (Exception $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    function tmpFill() {
        $gpName = 'Jens';
        $gpBody = 'Ik vind het heel goed!';
        $guestPost = new GuestPost($gpName, $gpBody, NULL);
        $this->add($guestPost);
    }

    private function assignId($guestPost) {
        $id = 0;
        if (!empty($this->_guestPosts)) {
            $id = count($this->_guestPosts);
        }
        try {
            $guestPost->setId($id);
            return $guestPost;
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    public function add($guestPost) {
        if (!$guestPost instanceof GuestPost) {
            throw new DBException('The object you tried to add was not a guestpost object', NULL);
        } 
        if ($this->containsId($guestPost->getId())) {
            throw new DBException('The database already contains a guestpost with this id', NULL);
        }
         if ($guestPost->getId() < 0) {
            $this->assignId($guestPost); //Should the action preformed her rethrow?(java would use 'throws' in method dec
        }
        $this->_guestPosts[$guestPost->getId()] = $guestPost;
    }

    public function containsId($id) {
        if (isset($this->_guestPosts) && !empty($this->_guestPosts)) {
            return array_key_exists($id, $this->_guestPosts);
        }
        return false;
    }

    public function get($id) {
         if (!$this->containsId($id)) {
            throw new DBException('could not find a guestpost with this id. id was: ' . $id, NULL);
        }
        return $this->_guestPosts[$id];
    }

    public function getByString($identifier) {
         $guestpostsWithName = array();
        foreach ($this->_guestPosts as $key => $guestPost) {
            if ($guestPost->getName() === $identifier) {
                $guestpostsWithName[$key] = $guestPost;
            }
        }
        return $guestpostsWithName;
    }

    public function getGuestPosts() {
        return $this->_guestPosts;
    }

    public function remove($id) {
        if(!$this->containsId($id)){
            throw new DBException('No guestpost with this id was found', NULL);
        }
        unset($this->_guestPosts[$id]);
    }

    public function getLastPosts($nrOfPosts) {
        $length = count($this->_guestPosts);
        $n = $length > $nrOfPosts ? $nrOfPosts*-1 : $length*-1;
        return array_slice($this->_guestPosts, $n);
    }

}
