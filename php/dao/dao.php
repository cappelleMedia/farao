<?php
//require_once dirname(__FILE__) . '/../errorhandling/dbexception.php';
interface Dao {

    public function add($daoObject);

    public function remove($id);

    public function containsId($id);

    public function get($id);

    public function getByString($identifier);
}
