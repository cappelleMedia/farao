<?php

//require_once 'userdao.php';

abstract class UserSqlDB extends SqlSuper implements UserDao {

    public function __construct($host, $username, $passwd, $database) {
        parent::__construct($host, $username, $passwd, $database);
    }
   
    public function add($user) {
        if (!$user instanceof User) {
            throw new DBException('The object you tried to add was not a user object', NULL);
        }
        if ($this->containsId($user->getId())) {
            throw new DBException('The database already contains a user with this id', NULL);
        }
        $query = 'INSERT INTO farao.users(user_name, user_email, hash_salt)';
        $query .= 'VALUES(:user_name, :user_email, :hash_salt);';
        $statement = parent::prepareStatement($query);
        $queryArgs = array(
            ':user_name' => $user->getUser_name(),
            ':user_email' => $user->getEmail(),
            ':hash_salt' => $user->getHash_salt()
        );
        $statement->execute($queryArgs);
    }

    public function containsId($id) {
        $query = 'SELECT * FROM farao.users WHERE user_id=?';
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
            throw new DBException('could not find a user with this id. id was: ' . $id, NULL);
        }
        $user = $this->createUser($result);
        return $user;
    }

    protected function createUser($row) {
        if (!$row) {
            throw new DBException('could not create user', NULL);
        }
        try {
            $user = new User($row['user_name'], 'use_hash', $row['user_email'], $row['hash_salt']);
            $user->setId($row['user_id']);
            return $user;
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    public function getUsers() {
        $query = "SELECT * FROM farao.users";
        $statement = parent::prepareStatement($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $users = array();
        foreach ($result as $row) {
            try {
                $user = $this->createUser($row);
                $users[$user->getId()] = $user;
            } catch (DomainModelException $ex) {
                throw new DBException($ex->getMessage(), $ex);
            }
        }
        return $users;
    }

    public function getByString($identifier) {
        $userWithName = '';
        try {
            $query = 'SELECT * FROM farao.users WHERE user_name = ?';
            $statement = parent::prepareStatement($query);
            $statement->bindParam(1, $identifier);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetchAll();
            foreach ($result as $row) {
                $user = $this->createUser($row);
                $userWithName = $user;
            }
        } catch (PDOException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
        return $userWithName;
    }

    public function remove($id) {
        if (!$this->containsId($id)) {
            throw new DBException('No user with this id was found', NULL);
        }
        $query = 'DELETE FROM farao.users WHERE user_id=?';
        $statement = parent::prepareStatement($query);
        $statement->bindParam(1, $id);
        $statement->execute();
    }

    public function updatePw($user_id, $pw_old, $pw_new) {
        try {
            $user = $this->get($user_id);
            $user->update($pw_old, $pw_new);
            $query = 'UPDATE farao.users SET hash_salt= :hash_salt WHERE users.user_id= :id;';
            $statement = parent::prepareStatement($query);
            $gueryArgs = array(
                ':hash_salt' => $user->getHash_salt(),
                ':id' => $user_id
            );
            $statement->execute($gueryArgs);
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

}
