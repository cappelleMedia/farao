<?php

//require_once 'userdao.php';
//require_once dirname(__FILE__) . '/../../model/user.php';

class UserMemDB implements UserDao {

    private $_users;

    public function __construct() {
        $this->init();
    }

    private function init() {
        $this->_users = array();
        try {
            $this->tmpFill();
        } catch (Exception $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    private function tmpFill() {
        $username = 'admin';
        $pw = 'Admin001';
        $email = 'cappelle.design@gmail.com';
        $user = new User($username, $pw, $email);
        $this->add($user);
    }

    private function assignId($user) {
        $id = 0;
        if (!empty($this->_users)) {
            $id = count($this->_users);
        }
        try {
            $user->setId($id);
            return $user;
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    public function add($user) {
        if (!$user instanceof User) {
            throw new DBException('The object you tried to add was not a user object', NULL);
        }
        if ($this->containsId($user->getId())) {
            throw new DBException('The database already contains a user with this id', NULL);
        }
        if ($user->getId() < 0) {
            $this->assignId($user); //Should the action preformed her rethrow?(java would use 'throws' in method dec
        }
        $this->_users[$user->getId()] = $user;
    }

    public function containsId($id) {
        if (isset($this->_users) && !empty($this->_users)) {
            return array_key_exists($id, $this->_users);
        }
        return false;
    }

    public function get($id) {
        if (!$this->containsId($id)) {
            throw new DBException('could not find a user with this id. id was: ' . $id, NULL);
        }
        return $this->_users[$id];
    }

    public function getByString($identifier) {
        $userWithName = '';
        foreach ($this->_users as $key => $user) {
            if ($user->getUser_name() === $identifier) {
                $userWithName = $user;
            }
        }
        return $userWithName;
    }

    public function getUsers() {
        return $this->_users;
    }

    public function remove($id) {
        if (!$this->containsId($id)) {
            throw new DBException('No beer with this id was found', NULL);
        }
        unset($this->_users[$id]);
    }

    public function updatePw($user_id, $pw_old, $pw_new) {
        try {
            $this->_users[$user_id]->update($pw_old, $pw_new);
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

}
