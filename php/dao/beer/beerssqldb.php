<?php

//require_once 'beersdao.php';

abstract class BeersSqlDB extends SqlSuper implements BeersDao {

    public function __construct($host, $username, $passwd, $database) {
        parent::__construct($host, $username, $passwd, $database);
    }

    public function add($beer) {
        if (!$beer instanceof Beer) {
            throw new DBException('The object you tried to add was not a beer object', NULL);
        }
        if ($this->containsId($beer->getId())) {
            throw new DBException('The database already contains a beer with this id', NULL);
        }
        if ($this->containsNameSameType($beer->getName(), $beer->getType())) {
            throw new DBException('It seems you have already added a beer for this name-type combination', NULL);
        }
        $query = 'INSERT INTO farao.beers(bier_name, bier_degrees, bier_brouwerij_name, bier_brouwerij_site, bier_type)';
        $query .= 'VALUES(:name, :deg, :brewName, :brewURL, :type);';
        $statement = parent::prepareStatement($query);
        $queryArgs = array(
            ':name' => $beer->getName(),
            ':deg' => $beer->getDegrees(),
            ':brewName' => $beer->getBrewery_name(),
            ':brewURL' => $beer->getBrewery_url(),
            ':type' => $beer->getType()
        );
        $statement->execute($queryArgs);
    }

    public function containsId($id) {
        $query = 'SELECT * FROM farao.beers WHERE bier_id=?';
        $statement = parent::prepareStatement($query);
        $statement->bindParam(1, $id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();
        return $result;
    }

    public function containsNameSameType($beerName, $beerType) {
        $query = 'SELECT * FROM farao.beers WHERE bier_name= :name AND bier_type= :type';
        if (!empty($beerName) && !empty($beerType)) {
            $statement = parent::prepareStatement($query);
            $queryArgs = array(
                ':name' => $beerName,
                ':type' => $beerType
            );
            $statement->execute($queryArgs);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetch();
            return $result;
        }
        return false;
    }

    public function get($id) {
        $result = $this->containsId($id)['0'];
        if (!$result || empty($result)) {
            throw new DBException('could not find a beer with this id. id was: ' . $id, NULL);
        }
        $beer = $this->createBeer($result);
        return $beer;
    }

    protected function getCleanDouble($string) {
        $dotPos = strrpos($string, '.');
        $commaPos = strrpos($string, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $string));
        }

        return floatval(
                preg_replace("/[^0-9]/", "", substr($string, 0, $sep)) . '.' .
                preg_replace("/[^0-9]/", "", substr($string, $sep + 1, strlen($string)))
        );
    }

    protected function getCleanBool($string) {
        return filter_var($string, FILTER_VALIDATE_BOOLEAN);
    }

    protected function converSpecialChars($string) {
        //TODO implement
        return $string;
    }

    protected function degreesAntiInject($degrees) {
        //format should become like this : x&comma;y &deg;
        // with x = double before comma and y is double after comma
    }

    protected function createBeer($row) {
        if (!$row) {
            throw new DBException('could not create beer', NULL);
        }
        try {
            $beer = new Beer($row['bier_name'], $this->getCleanDouble($row['bier_degrees']), $row['bier_brouwerij_name'], $row['bier_brouwerij_site'], $this->getCleanBool($row['bier_beschikbaar']), $row['bier_type']);
            $beer->setId($row['bier_id']);
            return $beer;
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    public function getBeersAll($admin) {

        $query = "SELECT * FROM farao.beers";
        if (!isset($admin) || $admin === '' || !$admin) {
            $query.= " WHERE bier_beschikbaar = true;";
        }
        $query.= ";";
        $statement = parent::prepareStatement($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $beers = array();
        foreach ($result as $row) {
            try {
                $beer = $this->createBeer($row);
                $beers[$beer->getId()] = $beer;
            } catch (DomainModelException $ex) {
                throw new DBException($ex->getMessage(), $ex);
            }
        }
        return $beers;
    }

    protected function getBeersType($type, $admin) {
        $query = "SELECT * FROM farao.beers WHERE bier_type=?";
        if (!$admin) {
            $query.=" AND bier_beschikbaar = true";
        }
        $query.=';';
        $statement = parent::prepareStatement($query);
        $statement->bindParam(1, $type);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $beers = array();
        foreach ($result as $row) {
            try {
                $beer = $this->createBeer($row);
                $beers[$beer->getId()] = $beer;
            } catch (DomainModelException $ex) {
                throw new DBException($ex->getMessage(), $ex);
            }
        }
        return $beers;
    }

    public function getBeersBottle($admin) {
        return $this->getBeersType('fles', $admin);
    }

    public function getBeersTap($admin) {
        return $this->getBeersType('vat', $admin);
    }

    public function getBeersTrappist($admin) {
        return $this->getBeersType('trappist', $admin);
    }

    public function getByString($identifier) {
        $beersWithName = array();
        try {
            $query = 'SELECT * FROM farao.beers WHERE bier_name= ?';
            $statement = parent::prepareStatement($query);
            $statement->bindParam(1, $identifier);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetchAll();
            foreach ($result as $row) {
                $beer = $this->createBeer($row);
                $beersWithName[$beer->getId()] = $beer;
            }
        } catch (PDOException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
        return $beersWithName;
    }

    public function remove($id) {
        if (!$this->containsId($id)) {
            throw new DBException('No beer with this id was found', NULL);
        }
        $query = 'DELETE FROM farao.beers WHERE bier_id=?';
        $statement = parent::prepareStatement($query);
        $statement->bindParam(1, $id);
        $statement->execute();
    }

    public function setAvailable($id, $available) {
        $query = 'UPDATE farao.beers SET bier_beschikbaar= :avail WHERE beers.bier_id= :id;';
        $statement = parent::prepareStatement($query);
        $queryArgs = array(
            ':avail' => $available ? 1 : 0,
            ':id' => $id
        );
        $statement->execute($queryArgs);
    }

    public function updateBeer($id, $name, $degrees, $brewery_name, $brewery_url, $available, $type) {
        $query = 'UPDATE farao.beers SET beers.bier_name= :name, beers.bier_degrees= :deg,' .
                'beers.bier_brouwerij_name= :brewName, beers.bier_brouwerij_site= :brewURL,' .
                'beers.bier_beschikbaar= :avail, beers.bier_type= :type'
                . ' WHERE beers.bier_id= :id;';
        $statement = parent::prepareStatement($query);
        $queryArgs = array(
            ':name' => $name,
            ':deg' => $degrees,
            ':brewName' => $brewery_name,
            ':brewURL' => $brewery_url,
            ':avail' => $available ? '1' : '0',
            ':type' => $type,
            ':id' => $id
        );
        $statement->execute($queryArgs);
    }

}
