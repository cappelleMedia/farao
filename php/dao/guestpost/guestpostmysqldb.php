<?php

//require_once 'guestpostsqldb.php';
//require_once dirname(__FILE__) . '/../../model/guestpost.php';

class GuestPostMysqlDB extends GuestPostSqlDB {

    public function __construct($host, $username, $passwd, $database) {
        parent::__construct('mysql:host=' . $host, $username, $passwd, $database);
        $this->init();
    }

    private function init() {
        $this->tableChecks();
    }

    private function tableChecks() {
        if (parent::tableExists('guestposts')) {
            
        } else {
            $query = " 
                CREATE TABLE IF NOT EXISTS farao.guestposts (
                    `guestpost_id` bigint(20) NOT NULL AUTO_INCREMENT, 
                    `guestpost_name` varchar(20) NOT NULL, 
                    `guestpost_body` varchar(200) NOT NULL, 
                    `guestpost_date` varchar(19) NOT NULL, 
                    PRIMARY KEY (`guestpost_id`)
                ) ENGINE = INNODB DEFAULT CHARSET = utf8 DEFAULT COLLATE utf8_unicode_520_ci AUTO_INCREMENT = 1;
            ";
            parent::executeInternalQuery($query);
        }
    }

}
