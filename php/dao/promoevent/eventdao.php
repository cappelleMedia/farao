<?php

interface EventDao extends Dao {
    
    public function getEvents();
    
    public function getNextEvents($nr);
    
    public function updateEvents($id, $eventTitle, $evenText, $format, $eventStart, $eventPic);
    
    public function addLineup($event_id, $lineupName, $lineupWeb, $lineupPic);
    
    public function removeLineup($event_id, $lineupId);
}
