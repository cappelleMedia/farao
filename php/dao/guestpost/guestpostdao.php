<?php
//require_once dirname(__FILE__) . '/../dao.php';

interface GuestPostDao extends Dao{
    
    public function getGuestPosts();
    public function getLastPosts($nrOfPosts);
}
