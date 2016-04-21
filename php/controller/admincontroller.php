<?php

//require_once dirname(__FILE__) . '/../controller/mastercontroller.php';

class AdminController extends MasterController {

    private $_faraoAdmin;
    private $_errorController;
    private $_validator;
    private $_beerController;
    private $_userController;
    private $_promoController;

    public function __construct($faraoAdmin) {
        $this->init($faraoAdmin);
    }

    private function init($faraoAdmin) {
        $this->_validator = new FormValidator();
        $this->_faraoAdmin = $faraoAdmin;
        $this->_beerController = new BeerController($faraoAdmin);
        $this->_userController = new UserController($faraoAdmin);
        $this->_errorController = new ErrorController();
        $this->_promoController = new PromoController($faraoAdmin);
    }

    public function processAdminRequest($action) {
        $root = 'admin/admin_portal';
        if (!parent::isLoggedOn()) {
            return 'admin/admin_loginPage.php';
        }
        if (parent::checkUserActivity()) {
            return $this->logout();
        } else {
            parent::updateUserActivity();
            if (strpos($action, 'Beer')) {
                return $this->processAdminBeerRequest($root, $action);
            }
            if (strpos($action, 'Promo')) {
                return $this->processAdminPromoRequest($root, $action);
            }
            return $this->processOtherRequest($root, $action);
        }
    }

    private function processOtherRequest($root, $action) {
        $nextPage = $root;
        switch ($action) {
            case 'admin_switchAvailable':
                $nextPage = 'beer_manager/' . $this->_beerController->switchBeerAvailable();
                break;
            case 'admin_updatePw' :
                $nextPage = $this->_userController->updateUserPw();
                break;
            default :
                $nextPage = 'NotFound';
                break;
        }
        return $nextPage;
    }

    private function processAdminBeerRequest($root, $action) {
        $nextPage = $root;
        switch ($action) {
            case 'admin_addBeerPage':
                $nextPage = 'admin/beer_manager_add.php';
                break;
            case 'admin_addNewBeer' :
                $nextPage = $this->_beerController->addBeer();
                break;
            case 'admin_updateBeerPage':
                $nextPage = $this->_beerController->updateBeerPage();
                break;
            case 'admin_updateBeer':
                $nextPage = $this->_beerController->updateBeer();
                break;
            case 'admin_deleteBeer' :
                $nextPage .= $this->_beerController->deleteBeer();
                break;
            default :
                $nextPage = 'NotFound';
                break;
        }
        return $nextPage;
    }

    private function processAdminPromoRequest($root, $action) {
        $nextPage = $root;
        switch ($action) {
            case 'admin_addPromoPage':
                $nextPage = 'admin/promo_manager_add.php';
                break;
            case 'admin_addNewPromo':
                $nextPage = $this->_promoController->addPromo();
                break;
            case 'admin_updatePromoPage':
                $nextPage = $this->_promoController->updatePromoPage();
                break;
            case 'admin_updatePromo' :
                $nextPage = $this->_promoController->updatePromo();
                break;
            case 'admin_deletePromo' :
                $nextPage = $this->_promoController->deletePromo();
                break;
            case 'admin_togglePromoActive' :
                $nextPage = $this->_promoController->setPromoIsActive();
                break;
            case 'admin_getJsonPromo' :
                $nextPage = $this->_promoController->getJsonPromo();
                break;
            case 'adminPromoRedirect':
                $nextPage = 'adminPromoRedirect';
                break;
            default :
                $nextPage = 'NotFound';
                break;
        }
        return $nextPage;
    }

}
