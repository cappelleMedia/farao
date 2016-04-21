<?php

//require_once dirname(__FILE__) . '/../dao/daofactory.php';
//require_once dirname(__FILE__) . '/../service/menuitem.php';
//require_once dirname(__FILE__) . '/../errorhandling/serviceexception.php';

class FaraoAdminService {

    private $_beerDB;
    private $_guestpostDB;
    private $_userDB;
    private $_promoDB;
    private $_menu;
    private $_daoFactory;

    public function __construct($configs) {
        $this->init($configs);
    }

    private function init($configs) {
        try {
            $this->_daoFactory = new DaoFactory();
            $this->_beerDB = $this->_daoFactory->getBeersDB($configs);
            $this->_guestpostDB = $this->_daoFactory->getGuestPostDB($configs);
            $this->_userDB = $this->_daoFactory->getUserDB($configs);
            $this->_promoDB = $this->_daoFactory->getPromoDB($configs);
            $this->createMenu();
        } catch (DBException $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    private function createMenu() {
        $menuAbout = new MenuItem('about', 'About', 'about.php');
        $menuBeers = new MenuItem('beers', 'Bieren', 'bieren.php');
        $menuPics = new MenuItem('pics', 'foto\'s', 'fotos.php');
        $menuPromos = new MenuItem('promos', 'Promo\'s', 'promos.php');
        $menuGuestbook = new MenuItem('guestbook', 'Gastenboek', 'gastenboek.php');
        $menuContact = new MenuItem('contact', 'Contact', 'contact.php');
        $this->_menu = array(
            'about' => $menuAbout,
            'beers' => $menuBeers,
            'pics' => $menuPics,
            'promos' => $menuPromos,
            'guestbook' => $menuGuestbook,
            'contact' => $menuContact);
    }

    public function getMenu() {
        return $this->_menu;
    }

    public function containsMenuItem($menuItem) {
        return array_key_exists($menuItem, $this->_menu);
    }

    //BEER FUNCTIONS
    public function addBeer($beer) {
        try {
            $this->_beerDB->add($beer);
        } catch (DBException $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function removeBeer($id) {
        try {
            $this->_beerDB->remove($id);
        } catch (DBException $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function containsBeerID($id) {
        return $this->_beerDB->containsId($id);
    }

    public function containsBeerName($beerName) {
        return $this->_beerDB->containsString($beerName);
    }

    public function containsBeerNameSameType($beerName, $beerType) {
        return $this->_beerDB->containsNameSameType($beerName, $beerType);
    }

    public function getBeer($id) {
        try {
            return $this->_beerDB->get($id);
        } catch (DBException $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getBeersWithName($beerName) {
        return $this->_beerDB->getByString($beerName);
    }

    public function getBeersAll($admin) {
        try {
            return $this->_beerDB->getBeersAll($admin);
        } catch (DBException $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getBeersBottle($admin) {
        try {
            return $this->_beerDB->getBeersBottle($admin);
        } catch (DBException $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getBeersTap($admin) {
        try {
            return $this->_beerDB->getBeersTap($admin);
        } catch (DBException $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getBeersTrappist($admin) {
        try {
            return $this->_beerDB->getBeersTrappist($admin);
        } catch (DBException $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function updateBeer($id, $name, $degrees, $brewery_name, $brewery_url, $available, $type) {
        try {
            $this->_beerDB->updateBeer($id, $name, $degrees, $brewery_name, $brewery_url, $available, $type);
        } catch (DBException $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function switchBeerAvailable($id, $available) {
        try {
            $this->_beerDB->setAvailable($id, $available);
        } catch (DBException $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    //guestpost functions
    public function addGuestpost($guestpost) {
        try {
            $this->_guestpostDB->add($guestpost);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function removeGuestpost($id) {
        try {
            $this->_guestpostDB->remove($id);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function containsGuestpostID($id) {
        return $this->_guestpostDB->containsId($id);
    }

    public function getGuestpost($id) {
        try {
            return $this->_guestpostDB->get($id);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getGuestpostsWithName($guestpostName) {
        try {
            return $this->_guestpostDB->getByString($guestpostName);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getGuestPosts() {
        try {
            return $this->_guestpostDB->getGuestPosts();
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getLatestGuestposts($nrOfPosts) {
        try {
            return $this->_guestpostDB->getLastPosts($nrOfPosts);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    //login functions

    public function getAuthenticatedUser($username, $pw) {
        try {
            $user = $this->_userDB->getByString($username);
            if ($user instanceof User && $user->authenticate($pw)) {
                return $user;
            } else {
                throw new ServiceException('Wrong password for this user', NULL);
            }
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    //user functions
    public function getUser($userId) {
        try {
            return $this->_userDB->getUser($userId);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getUserByUsername($username) {
        try {
            return $this->_userDB->getByString($username);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function updateUserPw($userId, $userPwOld, $userPwNew) {
        try {
            $this->_userDB->updatePw($userId, $userPwOld, $userPwNew);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    //promo funcions 
    public function addPromo($promo) {
        try {
            $this->_promoDB->add($promo);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function removePromo($id) {
        try {
            $this->_promoDB->remove($id);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function containsPromoId($id) {
        return $this->_promoDB->containsId($id);
    }

    public function getPromo($id) {
        try {
            return $this->_promoDB->get($id);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getPromoWithTitle($title) {
        try {
            return $this->_promoDB->getByString($title);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getPromos() {
        try {
            return $this->_promoDB->getPromos();
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function getCurrentPromos() {
        try {
            return $this->_promoDB->getCurrentPromos();
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function updatePromo($id, $title, $text, $format, $start, $end, $active) {
        try {
            $this->_promoDB->updatePromo($id, $title, $text, $format, $start, $end, $active);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

    public function setPromoActiveType($id, $active, $format, $newStart, $newEnd) {
        try {
            $promo = $this->getPromo($id);
            $start = $newStart ? $newStart : $promo->getStartStr($format);
            $end = $newEnd ? $newEnd : $promo->getEndStr($format);
            $this->updatePromo($id, $promo->getTitle(), $promo->getText(), $format, $start, $end, $active);
        } catch (Exception $ex) {
            throw new ServiceException($ex->getMessage(), $ex);
        }
    }

}
