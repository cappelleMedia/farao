<?php

//
//require_once dirname(__FILE__) . '/../service/faraoadminservice.php';
//require_once dirname(__FILE__) . '/../errorhandling/errorlogger.php';
//require_once dirname(__FILE__) . '/../controller/admincontroller.php';
//require_once dirname(__FILE__) . '/../controller/errorcontroller.php';
//require_once dirname(__FILE__) . '/../controller/usercontroller.php';
//require_once dirname(__FILE__) . '/../controller/beercontroller.php';
//require_once dirname(__FILE__) . '/../controller/gpcontroller.php';
//require_once dirname(__FILE__) . '/../controller/mastercontroller.php';

class Controller extends MasterController {

    private $_faraoAdmin;
    private $_configs;
    private $_adminController;
    private $_userController;
    private $_beerController;
    private $_gpController;
    private $_promoController;
    private $_errorController;
    private $_validator;

    public function __construct() {
        $this->init();
    }

    public function init() {
        $this->_errorController = new ErrorController();
        $this->_validator = new FormValidator();
        try {
            //$this->_configs = parse_ini_file(dirname(__FILE__) . '/../config/config.ini');
            $this->setConfigs('mysql');            
            if ($this->_configs['type.beers'] == 'memdb') {
                //SERVICE NIET MEER STANDAARD IN SESSION OMDAT PDO ERROR GEEFT OVER SERIALIZABLE
                parent::serviceSession();
            } else {
                $this->_faraoAdmin = new FaraoAdminService($this->_configs);
            }
            $this->_adminController = new AdminController($this->_faraoAdmin);
            $this->_userController = new UserController($this->_faraoAdmin);
            $this->_beerController = new BeerController($this->_faraoAdmin);
            $this->_gpController = new GpController($this->_faraoAdmin);
            $this->_promoController = new PromoController($this->_faraoAdmin);
        } catch (Exception $ex) {
            require_once(dirname(__FILE__) . '/../../pages/' . $this->_errorController->errLog($ex));
            die();
        }
    }

    private function setConfigs($dbType) {
        //general sql configs
        $this->_configs['username'] = 'farao_admin';
        $this->_configs['password'] = 'Admin001';
        $this->_configs['database'] = 'farao';
        if ($dbType == 'mysql') {
            $this->_configs['type.beers'] = 'mysql';
            $this->_configs['type.guestposts'] = 'mysql';
            $this->_configs['type.users'] = 'mysql';
            $this->_configs['type.promos'] = 'mysql';
            $this->_configs['host'] = '127.0.0.1';        
        } else {
            $this->_configs['type.beers'] = 'memdb';
            $this->_configs['type.guestposts'] = 'memdb';
            $this->_configs['type.users'] = 'memdb';
        }
    }

    public function processRequest() {
        $nextPage = 'home.php';
        $action = 'home';
        if ($this->getAction()) {
            $action = $this->getAction();
        }
        if (strpos($action, 'admin') !== FALSE) {
            $nextPage = $this->processAdminPage($action);
        } else if ($action === 'home' || $this->_faraoAdmin->containsMenuItem($action)) {
            $nextPage = $this->processMenuPage($action);
        } else {
            $nextPage = $this->processOtherPage($action);
        }
        if (!$nextPage) {
            $nextPage = 'error_pages/server_error.php';
        }
        require_once(dirname(__FILE__) . '/../../pages/' . $nextPage);
    }

    private function processAdminPage($action) {
        $nextPage = 'admin/admin_portal';
        if (!parent::isLoggedOn()) {
            return 'admin/admin_loginPage.php';
        }
        if (parent::checkUserActivity()) {
            return $this->_userController->logout();
        } else {
            parent::updateUserActivity();
            switch ($action) {
                case 'adminPortal':
                    $nextPage .= '.php';
                    break;
                case 'adminBeerMgr':
                    $nextPage = 'admin/admin_beermgr.php';
                    break;
                case 'adminPromoMgr':
                    $nextPage = 'admin/admin_promomgr.php';
                    break;
                case 'adminEventMgr':
                    $nextPage = 'admin/admin_eventmgr.php';
                    break;
                default :
                    $nextPage = $this->_adminController->processAdminRequest($action);
                    break;
            }
            if (strpos($nextPage, 'HomeRedirect')) {
                header('Location: index.php?action=adminPortal');
                exit(0);
            } else if (strpos($nextPage, 'BeerRedirect')) {
                header('Location: index.php?action=adminBeerMgr');
                exit(0);
            } else if(strpos($nextPage, 'PromoRedirect')) {
                header('Location: index.php?action=adminPromoMgr');
                exit(0);
            } else if (strpos($nextPage, 'otFound')) {
                return $this->_errorController->toErrorPage('De opgevraagde admin pagina werd niet gevonden', '');
            } else {
                return $nextPage;
            }
        }
    }

    private function processMenuPage($action) {
        $nextPage = '';
        if ($action === 'home') {
            $nextPage = 'home.php';
        } else {
            $menu = $this->_faraoAdmin->getMenu();
            $nextPage = $menu[$action]->getPageName();
        }
        if (empty($nextPage)) {
            $nextPage = $this->_errorController->toErrorPage('De opgevraagde pagina werd niet gevonden', '');
        }
        return $nextPage;
    }

    private function processOtherPage($action) {
        $nextPage = '';
        switch ($action) {
            case 'disclaimer':
                $nextPage = 'disclaimer.php';
                break;
            case 'addGp':
                $nextPage = $this->_gpController->addGuestpost();
                break;
            case 'login' :
                $nextPage = $this->_userController->login();
                break;
            case 'logout' :
                $nextPage = $this->_userController->logout();
                break;
            default :
                $nextPage = $this->_errorController->toErrorPage('De opgevraagde pagina werd niet gevonden', '');
                break;
        }
        return $nextPage;
    }

    public function getAdminController() {
        return $this->_adminController;
    }

    public function getUserController() {
        return $this->_userController;
    }

    public function getBeerController() {
        return $this->_beerController;
    }

    public function getGpController() {
        return $this->_gpController;
    }
    
    public function getPromoController() {
        return $this->_promoController;
    }

    public function getErrorController() {
        return $this->_errorController;
    }

    public function getValidator() {
        return $this->_validator;
    }

    private function getAction() {
        $pure = '';
        if (isset($_GET['action'])) {
            $pure = $_GET['action'];
        }
        $filtered = filter_var($pure, FILTER_SANITIZE_STRING);
        $entit = htmlentities($filtered, ENT_QUOTES);
        return $entit;
    }

}
