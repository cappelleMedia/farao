<?php

class BeerController {

    private $_faraoAdmin;
    private $_errorController;
    private $_validator;

    public function __construct($faraoAdmin) {
        $this->init($faraoAdmin);
    }

    private function init($faraoAdmin) {
        $this->_validator = new FormValidator();
        $this->_faraoAdmin = $faraoAdmin;
        $this->_errorController = new ErrorController();
    }

    public function getBeersBottle($admin) {
        try {
            return $this->_faraoAdmin->getBeersBottle($admin);
        } catch (ServiceException $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    public function getBeersTap($admin) {
        try {
            return $this->_faraoAdmin->getBeersTap($admin);
        } catch (ServiceException $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    public function getBeersTrappist($admin) {
        try {
            return $this->_faraoAdmin->getBeersTrappist($admin);
        } catch (ServiceException $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    public function getBeersAll($admin) {
        try {
            return $this->_faraoAdmin->getBeersAll($admin);
        } catch (Exception $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    //admin functions
    public function updateBeer() {
        $beerArr = $this->getBeerArr();
        $beerArr['bId'] = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'beerIdField'));
        global $beerFormData;
        $beerFormData = $this->_validator->validateupdateBeerForm($beerArr, $this->_faraoAdmin);
        try {
            $beerTest = $this->createBeer($beerArr); //FIXME not efficient
        } catch (DomainModelException $ex) {
            return 'admin/beer_manager_update.php';
        }
        if (array_key_exists('extraMessage', $beerFormData)) {
            return 'admin/beer_manager_update.php';
        }
        try {
            $this->_faraoAdmin->updateBeer($beerFormData['beerIdPrevVal'], $beerTest->getName(), $beerTest->getDegrees(), $beerTest->getBrewery_name(), $beerTest->getBrewery_url(), $beerTest->getAvailable(), $beerTest->getType());
            return 'adminBeerRedirect';
        } catch (ServiceException $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    public function addBeer() {
        $beerArr = $this->getBeerArr();
        global $beerFormData;
        $beerFormData = $this->_validator->validateAddBeerForm($beerArr, $this->_faraoAdmin);
        try {
            $beerToAdd = $this->createBeer($beerArr);
        } catch (DomainModelException $ex) {
            return 'admin/beer_manager_add.php';
        }
        if (array_key_exists('extraMessage', $beerFormData)) {
            return 'admin/beer_manager_add.php';
        }
        try {
            $this->_faraoAdmin->addBeer($beerToAdd);
            return 'adminBeerRedirect';
        } catch (ServiceException $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    public function deleteBeer() {
        try {
            $beerId = $this->_validator->sanitizeInput($_GET['beerId']);
            $this->_faraoAdmin->removeBeer($beerId);
            return 'adminBeerRedirect';
        } catch (Exception $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    public function switchBeerAvailable() {
        try {
            $beerId = $this->_validator->sanitizeInput($_POST['switchId']);
            $available = $this->_validator->sanitizeInput($_POST['availableVal']) == 'JA' ? true : false;
            $this->_faraoAdmin->switchBeerAvailable($beerId, $available);
            return 'adminBeerRedirect';
        } catch (ServiceException $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    private function getBeerArr() {
        if (isset($_POST['beerName']) && isset($_POST['beerDeg']) && isset($_POST['brewName']) &&
                isset($_POST['brewUrl']) && isset($_POST['beerAvailable']) && isset($_POST['beerType'])) {
            $name = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'beerName'));
            $deg = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'beerDeg'));
            $bName = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'brewName'));
            $bUrl = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'brewUrl'));
            $available = ( $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'beerAvailable'))) === 'JA' ? TRUE : FALSE;
            $type = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'beerType'));
        }
        $beerArr = array(
            'beerName' => $name,
            'beerDeg' => $deg,
            'brewName' => $bName,
            'brewUrl' => $bUrl,
            'beerAvailable' => $available,
            'beerType' => $type
        );
        return $beerArr;
    }

    private function createBeer($beerArr) {
        $beerToAdd = new Beer(
                $beerArr['beerName'], $beerArr['beerDeg'], $beerArr['brewName'], $beerArr['brewUrl'], $beerArr['beerAvailable'], $beerArr['beerType']);
        return $beerToAdd;
    }

    public function getBeer($beerId) {
        return $this->_faraoAdmin->getBeer($beerId);
    }

    public function updateBeerPage() {
        try {
            $beerId = $this->_validator->sanitizeInput($_GET['beerId']);
            $_GET['updateBeer'] = $this->getBeer($beerId);
            return 'admin/beer_manager_update.php';
        } catch (ServiceException $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }
}
