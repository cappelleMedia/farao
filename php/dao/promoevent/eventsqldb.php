<?php

abstract class eventsqldb extends SqlSuper implements EventDao {
    
    public function __construct($host, $username, $passwd, $database) {
        parent::__construct($host, $username, $passwd, $database);
    }
    
    protected function createEvent($row, $lineup) {
        if(!$row) {
            throw new DBException('could not create event', NULL);
        }
        try {
            
        } catch (Exception $ex) {

        }
    }
}
