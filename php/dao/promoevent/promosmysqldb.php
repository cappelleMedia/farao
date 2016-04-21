<?php

class PromosMysqlDB extends PromosSqlDB {

    public function __construct($host, $username, $passwd, $database) {
        parent::__construct('mysql:host=' . $host, $username, $passwd, $database);
        $this->init();
    }

    private function init() {
        $this->tablechecks();
    }

    private function tableChecks() {
        if (parent::tableExists('promos')) {
            
        } else {
            $query = "CREATE TABLE IF NOT EXISTS farao.promos (" .
                    "`promo_id` bigint(20) NOT NULL AUTO_INCREMENT, " .
                    "`promo_title` VARCHAR(155) NOT NULL, " .
                    "`promo_text` TEXT NOT NULL, " .
                    "`promo_start` DATE NOT NULL, " .
                    "`promo_end` DATE NOT NULL, " .
                    "`promo_active` BOOL NOT NULL, ".
                    "PRIMARY KEY(`promo_id`)) " .
                    "ENGINE = INNODB DEFAULT CHARSET = utf8 DEFAULT COLLATE utf8_unicode_520_ci AUTO_INCREMENT = 1;";
            parent::executeInternalQuery($query);
        }
    }
}
