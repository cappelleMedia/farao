<?php

//require_once 'usersqldb.php';
//require_once dirname(__FILE__) . '/../../model/user.php';

class UserMysqlDB extends UserSqlDB {

    public function __construct($host, $username, $passwd, $database) {
        parent::__construct('mysql:host=' . $host, $username, $passwd, $database);
        $this->init();
    }

    private function init() {
        $this->tableChecks();
    }

    private function tableChecks() {
        if (parent::tableExists('users')) {
            
        } else {
            echo '<script>console.log("users table created");</script>';
            $query = " 
                CREATE TABLE IF NOT EXISTS farao.users(
                  `user_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                  `user_name` VARCHAR(40) NOT NULL UNIQUE,
                  `user_email` VARCHAR(50) NOT NULL UNIQUE,
                  `hash_salt` VARCHAR(60) NOT NULL,
                  PRIMARY KEY(`user_id`)
               ) ENGINE = INNODB DEFAULT CHARSET = utf8 DEFAULT COLLATE utf8_unicode_520_ci AUTO_INCREMENT = 1;
            ";
            parent::executeInternalQuery($query);
            $user = new User('root', 'Root009', 'cappelle.design@gmail.com');
            parent::add($user);
            
            $user = new User('admin', 'Admin001', 'info@farao.be');
            parent::add($user);
        }
    }

}
