<?php

//
//include_once dirname(__FILE__) . '/beer/beersmemdb.php';
//include_once dirname(__FILE__) . '/beer/beersmysqldb.php';
//include_once dirname(__FILE__) . '/guestpost/guestpostmemdb.php';
//include_once dirname(__FILE__) . '/guestpost/guestpostmysqldb.php';
//include_once dirname(__FILE__) . '/user/usermemdb.php';
//include_once dirname(__FILE__) . '/user/usermysqldb.php';

class DaoFactory {

    public function __construct() {
        
    }

    public function getSupportedTypes() {
        $supported = array('memdb', 'mysql');
        return $supported;
    }

    public function getUserDB($configs) {
        $this->checkConfigs('users', $configs);
        $dbType = $configs['type.users'];
        $userDB = NULL;
        switch ($dbType) {
            case 'memdb':
                $userDB = new UserMemDB();
                break;
            case 'mysql' :
                $userDB = new UserMysqlDB($configs['host'], $configs['username'], $configs['password'], $configs['database']);
                break;
            default :
                throw new DBException('This type of database is not (yet) supported for users: ' . $dbType, NULL);
        }
        return $userDB;
    }

    public function getGuestPostDB($configs) {
        $this->checkConfigs('guestposts', $configs);
        $dbType = $configs['type.guestposts'];
        $guestpostDB = NULL;
        switch ($dbType) {
            case 'memdb':
                $guestpostDB = new GuestPostMemDB();
                break;
            case 'mysql' :
                $guestpostDB = new GuestPostMysqlDB($configs['host'], $configs['username'], $configs['password'], $configs['database']);
                break;
            default :
                throw new DBException('This type of database is not (yet) supported for guestposts: ' . $dbType, NULL);
        }
        return $guestpostDB;
    }

    public function getBeersDB($configs) {
        $this->checkConfigs('beers', $configs);
        $dbType = $configs['type.beers'];
        $beersDb = NULL;
        switch ($dbType) {
            case 'memdb':
                $beersDb = new BeersMemDB();
                break;
            case 'mysql' :
                $beersDb = new BeersMysqlDB($configs['host'], $configs['username'], $configs['password'], $configs['database']);
                break;
            default :
                throw new DBException('This type of database is not (yet) supported for beers: ' . $dbType, NULL);
        }
        return $beersDb;
    }

    public function getPromoDB($configs) {
        $this->checkConfigs('promos', $configs);
        $dbType = $configs['type.promos'];
        $promosDB = NULL;
        switch ($dbType) {
            case 'memdb':
                $promosDB = new PromoMemDB();
                break;
            case 'mysql' :
                $promosDB = new PromosMysqlDB($configs['host'], $configs['username'], $configs['password'], $configs['database']);
                break;
            default :
                throw new DBException('This type of database is not (yet) supported for promos: ' . $dbType, NULL);
        }
        return $promosDB;
    }

    private function checkConfigs($type, $configs) {
        if (!isset($configs) || !is_array($configs) || empty($configs)) {
            throw new DBException('No valid configuration found', NULL);
        }
        if (!array_key_exists('type.' . $type, $configs)) {
            throw new DBException('No config found for ' . $type . ' database type', NULL);
        }
    }

}
