<?php

//require_once 'guestpostdao.php';

abstract class GuestPostSqlDB extends SqlSuper implements GuestPostDao {

    public function __construct($host, $username, $passwd, $database) {
        parent::__construct($host, $username, $passwd, $database);
    }

    public function add($guestpost) {
        if (!$guestpost instanceof GuestPost) {
            throw new DBException('The object you tried to add was not a guestpost object', NULL);
        }
        if ($this->containsId($guestpost->getId())) {
            throw new DBException('The database already contains a guestpost with this id', NULL);
        }
        $d = $guestpost->getDateTime();
        $query = 'INSERT INTO farao.guestposts(guestpost_name, guestpost_body, guestpost_date)';
        $query .= 'VALUES(:name, :body, :date);';
        $statement = parent::prepareStatement($query);
        $queryArgs = array(
            ':name' => $guestpost->getName(),
            ':body' => $guestpost->getBody(),
            ':date' => $d
        );
        $statement->execute($queryArgs);
    }

    public function containsId($id) {
        $query = 'SELECT * FROM farao.guestposts WHERE guestpost_id=?';
        $statement = parent::prepareStatement($query);
        $statement->bindParam(1, $id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();
        return $result;
    }

    public function get($id) {
        $result = $this->containsId($id)['0'];
        if (!$result || empty($result)) {
            throw new DBException('could not find a guestpost with this id. id was: ' . $id, NULL);
        }
        $guestpost = $this->createPost($result);
        return $guestpost;
    }
    
    protected function createPost($row) {
        if (!$row) {
            throw new DBException('could not create guestpost', NULL);
        }
        try {
//            $date = date('d/m/Y H:i:s ', $date);
            $guestpost = new GuestPost($row['guestpost_name'], $row['guestpost_body'], $row['guestpost_date']);
            $guestpost->setId($row['guestpost_id']);
            return $guestpost;
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    public function getGuestPosts() {
        $query = "SELECT * FROM farao.guestposts ORDER BY farao.guestposts.guestpost_date DESC";
        $statement = parent::prepareStatement($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $guestposts = array();
        foreach ($result as $row) {
            try {
                $guestpost = $this->createPost($row);
                $guestposts[$guestpost->getId()] = $guestpost;
            } catch (DomainModelException $ex) {
                throw new DBException($ex->getMessage(), $ex);
            }
        }
        return $guestposts;
    }
    public function getLastPosts($nrOfPosts) {
        $query = "SELECT * FROM `guestposts` ORDER BY farao.guestposts.guestpost_date DESC LIMIT ?";
        parent::getConnection()->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $statement = parent::prepareStatement($query);
        $statement->bindParam(1, $nrOfPosts);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();
        $guestposts = array();
        foreach ($result as $row) {
            try {
                $guestpost = $this->createPost($row);
                $guestposts[$guestpost->getId()] = $guestpost;
            } catch (DomainModelException $ex) {
                throw new DBException($ex->getMessage(), $ex);
            }
        }
        return $guestposts;
    }

    public function getByString($identifier) {
        $guestpostsWithName = array();
        try {
            $query = 'SELECT * FROM farao.guestposts WHERE guestpost_name =?';
            $statement = parent::prepareStatement($query);
            $statement->bindParam(1, $identifier);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetchAll();
            foreach ($result as $row) {
                $guestpost = $this->createPost($row);
                $guestpostsWithName[$guestpost->getId()] = $guestpost;
            }
        } catch (PDOException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
        return $guestpostsWithName;
    }

    public function remove($id) {
        if (!$this->containsId($id)) {
            throw new DBException('No guestpost with this id was found', NULL);
        }
        $query = 'DELETE FROM farao.guestposts WHERE guestpost_id=?';
        $statement = parent::prepareStatement($query);
        $statement->bindParam(1, $id);
        $statement->execute();
    }

}

//
//$dateTime = date('d/m/Y H:i:s', time());
//echo $dateTime . "\n";
//$date = date('Y-d-m H:i:s', strtotime(str_replace('-', '/', $dateTime)));
//echo $date . "\n";
//$date2 = date('d/m/Y H:i:s',strtotime($date));
//echo $date2;
