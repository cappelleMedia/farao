<?php

//
//require_once 'beerssqldb.php';
//require_once dirname(__FILE__) . '/../../model/beer.php';

class BeersMysqlDB extends BeersSqlDB {

    public function __construct($host, $username, $passwd, $database) {
        parent::__construct('mysql:host=' . $host, $username, $passwd, $database);
        $this->init();
    }

    private function init() {
        $this->tableChecks();
    }

    private function tableChecks() {
        if (parent::tableExists('beers')) {
            
        } else {
            $query = "
CREATE TABLE IF NOT EXISTS farao.beers (
  `bier_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bier_name` varchar(155) NOT NULL,
  `bier_degrees` DOUBLE NOT NULL,
  `bier_brouwerij_name` varchar(155) NOT NULL,
  `bier_brouwerij_site` varchar(155) DEFAULT 'bier_fles_brouwerij_name',
  `bier_beschikbaar` tinyint(1) DEFAULT '1',
  `bier_type` enum('fles','vat','trappist') DEFAULT NULL,
  PRIMARY KEY (`bier_id`)
  ) ENGINE = INNODB DEFAULT CHARSET = utf8 DEFAULT COLLATE utf8_unicode_520_ci AUTO_INCREMENT = 1;
";
            parent::executeInternalQuery($query);
        }
    }

}
