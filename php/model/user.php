<?php
//
//require_once dirname(__FILE__) . '/daoobject.php';
//require_once dirname(__FILE__) . '/../errorhandling/domainexception.php';

class User implements DaoObject, JsonSerializable {

    private $_id = -1;
    private $_user_name;
    private $_user_pw;
    private $_email; //TODO add validation
    private $_hash_salt;

    public function __construct($user_name = '', $user_pw = 'use_hash', $email = '', $hash_salt = 'not_set') {
        $this->setUser_name($user_name);
        $this->setUser_pw($user_pw);
        $this->setEmail($email);
        $this->setHash_salt($hash_salt);
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

    public function setUser_name($user_name) {
        if (!(trim($user_name))) {
            throw new DomainModelException('Name is a required field', NULL);
        }
        $this->_user_name = $user_name;
    }

    public function setUser_pw($user_pw) {
        if ($user_pw !== 'use_hash') {
            if (!(trim($user_pw))) {
                throw new DomainModelException('Name is a required field', NULL);
            }
            if (strlen($user_pw) < 5) {
                throw new DomainModelException('Pasword must be at least 5 characters long!');
            }
            $uppercase = preg_match('@[A-Z]@', $user_pw);
            $lowercase = preg_match('@[a-z]@', $user_pw);
            $number = preg_match('@[0-9]@', $user_pw);
            if (!$uppercase) {
                throw new DomainModelException('Password should at least contain one uppercase letter');
            }
            if (!$lowercase) {
                throw new DomainModelException('Password should at least contain one lowercase letter');
            }
            if (!$number) {
                throw new DomainModelException('Password should at least contain one number');
            }
            $this->_user_pw = $user_pw;
            $this->setHash_salt(password_hash($user_pw, PASSWORD_BCRYPT));
        }
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainModelException($email . ' is not a valid email address');
        }
        $this->_email = $email;
    }

    public function setHash_salt($hash_salt) {
        if ($hash_salt !== 'not_set') {
            $this->_hash_salt = $hash_salt;
        }
    }

    public function getUser_name() {
        return $this->_user_name;
    }

    public function getUser_pw() {
        return $this->_user_pw;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function getHash_salt() {
        return $this->_hash_salt;
    }

    public function getId() {
        return $this->_id;
    }

    public function authenticate($password) {
        return password_verify($password, $this->getHash_salt());
    }

    public function update($pw_old, $pw_new) {
        if (!$this->authenticate($pw_old)) {
            throw new DomainModelException('Wrong password for this user');
        }
        $this->setUser_pw($pw_new);
    }

    public function jsonSerialize() {
        $arr = array();
        $arr['user_id'] = $this->_id;
        $arr['user_name'] = $this->_user_name;
        $arr['user_email'] = $this->_email;
        $arr['user_hash_salt'] = $this->_hash_salt;
        return $arr;
    }       
}
