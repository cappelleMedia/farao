<?php

abstract class PromosSqlDB extends SqlSuper implements PromoDao {

    public function __construct($host, $username, $passwd, $database) {
        parent::__construct($host, $username, $passwd, $database);
    }

    protected function createPromo($row) {
        if (!$row) {
            throw new DBException('could not create promo', NULL);
        }
        try {
            $promo = new Promo($row['promo_title'], $row['promo_text'], $this->getSqlDateFormat(), $row['promo_start'], $row['promo_end']);
            $promo->setId($row['promo_id']);
            $promo->setActive($row['promo_active']);
            return $promo;
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
    }

    public function add($promo) {
        if (!$promo instanceof Promo) {
            throw new DBException('The object you tried to add was not a promo object', NULL);
        }
        if ($this->containsId($promo->getId())) {
            throw new DBException('The database already contains a promo with this id', NULL);
        }
        $start = $promo->getStartStr($this->getSqlDateFormat());
        $end = $promo->getEndStr($this->getSqlDateFormat());
        $query = 'INSERT INTO farao.promos(promo_title, promo_text, promo_start, promo_end, promo_active)';
        $query .= 'VALUES(:title, :text, :start, :end, :active);';
        $statement = parent::prepareStatement($query);
        $queryArgs = array(
            ':title' => $promo->getTitle(),
            ':text' => $promo->getText(),
            ':start' => $start,
            ':end' => $end,
            ':active' => true
        );
        $statement->execute($queryArgs);
    }

    public function containsId($id) {
        $query = 'SELECT * FROM farao.promos WHERE promo_id=?';
        $statement = parent::prepareStatement($query);
        $statement->bindParam(1, $id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();
        return $result;
    }

    public function get($id) {                
        $result = $this->containsId($id)[0];
        if (!$result || empty($result)) {
            throw new DBException('could not find a promo with this id. id was: ' . $id, NULL);
        }
        $promo = $this->createPromo($result);
        return $promo;
    }

    public function getByString($identifier) {
        $promoWithTitle = array();
        try {
            $query = 'SELECT * FROM farao.promos WHERE promo_title =?';
            $statement = parent::prepareStatement($query);
            $statement->bindParam(1, $identifier);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetchAll();
            foreach ($result as $row) {
                $promo = $this->createPromo($row);
                $promoWithTitle[$promo->getId()] = $promo;
            }
        } catch (PDOException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        } catch (DomainModelException $ex) {
            throw new DBException($ex->getMessage(), $ex);
        }
        return $promoWithTitle;
    }

    public function getCurrentPromos() {
        $query = "SELECT * FROM farao.promos WHERE CURRENT_DATE BETWEEN promo_start AND promo_end";
        return $this->getPromosFromQuery($query);
    }

    public function getPromos() {
        $query = "SELECT * FROM farao.promos";
        return $this->getPromosFromQuery($query);
    }

    private function getPromosFromQuery($query) {
        $statement = parent::prepareStatement($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $promos = array();
        foreach ($result as $row) {
            try {
                $promo = $this->createPromo($row);
                $promos[$promo->getId()] = $promo;
            } catch (DomainException $ex) {
                throw new DBException($ex->getMessage(), $ex);
            }
        }
        return $promos;
    }

    public function remove($id) {
        if(!$this->containsId($id)) {
            throw new DBException('No promo with this id was found', NULL);
        }
        $query = 'DELETE FROM farao.promos WHERE promo_id=?';
        $statement = parent::prepareStatement($query);
        $statement->bindParam(1,$id);
        $statement->execute();
    }

    public function updatePromo($id, $promoTitle, $promoText, $format, $promoStart, $promoEnd, $active) {
        $query = 'UPDATE farao.promos SET '.
                'promos.promo_title= :title, '.
                'promos.promo_text= :desc, '.
                'promos.promo_start= :start, '.
                'promos.promo_end= :end, '.
                'promos.promo_active= :active '.
                'WHERE promos.promo_id= :id';
        $dStart = DateTime::createFromFormat($format, $promoStart);
        $dEnd = DateTime::createFromFormat($format, $promoEnd);
        $statement = parent::prepareStatement($query);
        $queryArgs = array(
            ':title' => $promoTitle,
            ':desc' => $promoText,
            ':start' => $dStart->format('Y-m-d'),
            ':end' => $dEnd->format('Y-m-d'),
            ':active' => $active ? '1' : '0',
            ':id' => $id
        );
        $statement->execute($queryArgs);
    }

}
