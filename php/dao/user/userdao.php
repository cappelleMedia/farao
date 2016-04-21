<?php
//require_once dirname(__FILE__) . '/../dao.php';

interface UserDao extends Dao {
    
    public function getUsers();
    public function updatePw($user_id, $pw_old, $pw_new);
        
}
