<?php

class PromoController {

    private $_faraoAdmin;
    private $_errorController;
    private $_validator;
    private $_beFormat = 'd/m/Y';

    public function __construct($faraoAdmin) {
        $this->init($faraoAdmin);
    }

    private function init($faraoAdmin) {
        $this->_validator = new FormValidator();
        $this->_faraoAdmin = $faraoAdmin;
        $this->_errorController = new ErrorController();
    }

    //json handling
    function isJson() {
        if (isset($_GET['isJson']) && $_GET['isJson'] === 't') {
            $data = $this->_faraoAdmin->getPromos();
            $_POST['jsonData'] = $data;
            return true;
        }
        return false;
    }

    function getJsonPromo() {
        if (isset($_POST['promoId']) && $_POST['promoId']) {
            $id = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'promoId'));
            $data = $this->_faraoAdmin->getPromo($id);
            $_POST['jsonData'] = $data;
            return 'admin/jsondatahandler.php';
        } else {
            $this->_errorController->goToErrorPage(new ControllerException('No id  was given for a json promo', NULL));
        }
    }

    //Start aiding functions
    public function isActivePromo($promo) {
        return $promo->isActive();
    }

    public function isCurrentPromo($promo) {
        $now = DateTime::createFromFormat($this->_beFormat, date($this->_beFormat));
        return ($now >= $promo->getStart()) && ($now <= $promo->getEnd());
    }

    public function getPromoStatus($promo) {
        $now = DateTime::createFromFormat($this->_beFormat, date($this->_beFormat));
        $display = 'displayName';
        $code = 'code';
        $status = array($display => '', $code => '');
        if ($this->isCurrentPromo($promo)) {
            $this->getCurrentStatus($promo, $display, $code, $status);
        } elseif ($now < $promo->getStart()) {
            $this->getUpcomingStatus($promo, $display, $code, $status);
        } else {
            $status[$display] = 'Voorbij';
            $status[$code] = '30';
        }
        return $status;
    }

    private function getCurrentStatus($promo, $display, $code, &$status) {
        $status[$code] = '1';
        if ($this->isActivePromo($promo)) {
            $status[$display] = 'Actief';
            $status[$code] .= '0';
        } else {
            $status[$display] = 'Gepauzeerd';
            $status[$code] .= '1';
        }
    }

    private function getUpcomingStatus($promo, $display, $code, &$status) {
        $status[$display] = 'Aankomend';
        $status[$code] = '2';
        if ($this->isActivePromo($promo)) {
            $status[$display] .= ' | Geactiveerd';
            $status[$code] .= '0';
        } else {
            $status[$display] = ' | Gepauzeerd';
            $status[$code] .= '1';
        }
    }

    public function getStatusIcon($statusCode, $promo) {
        switch ($statusCode) {
            case '10' :
                return $this->getStatusIconActive($promo);
            case '20':
            case '30':
            case '11':
                return $this->getStatusIconInactive($promo);
        }
    }

    private function getStatusIconActive($promo) {
        $activated = '<a href="index.php?action=admin_togglePromoActive&promoId=' . $promo->getId() . '&promoActive=1" class="btn btn-sm btn-warning confirmation-trigger" ' .
                'data-confirmation-type="promo-deactivate"> ' .
                '<i class="fa fa-pause fa-lg"></i>' .
                '</a>';
        return $activated;
    }

    private function getStatusIconInactive($promo) {
        $deactivated = '<a href="index.php?action=admin_togglePromoActive&promoId=' . $promo->getId() . '&promoActive=0" class="btn btn-sm btn-success confirmation-trigger" ' .
                'data-confirmation-type="promo-activate"> ' .
                '<i class="fa fa-play fa-lg"></i>' .
                '</a>';
        return $deactivated;
    }

    //End aiding functions

    public function getPromo($promoId) {
        try {
            return $this->_faraoAdmin->getPromo($promoId);
        } catch (ServiceException $ex) {
            $this->_errorController->goToErrorPage($ex);
        }
    }

    public function getPromos($admin) {
        try {
            if ($admin) {
                return $this->_faraoAdmin->getPromos();
            } else {
                return $this->_faraoAdmin->getCurrentPromos();
            }
        } catch (ServiceException $ex) {
            $this->_errorController->goToErrorPage($ex);
        }
    }

    public function getPromoWithTitle($title) {
        try {
            return $this->_faraoAdmin->getPromoWithTitle($title);
        } catch (Exception $ex) {
            
        }
    }

    public function deletePromo() {
        try {
            $promoId = $this->_validator->sanitizeInput($_GET['promoId']);
            $this->_faraoAdmin->removePromo($promoId);
            return $this->isJson() ? 'admin/jsondatahandler.php' : 'adminPromoRedirect';
        } catch (Exception $ex) {
            $this->_errorController->goToErrorPage($ex);
        }
    }

    public function addPromo() {
        try {
            $promoArr = $this->getPromoArr();
        } catch (Exception $ex) {
            $this->_errorController->goToErrorPage($ex);
        }
        global $promoFormData;
        $promoFormData = $this->_validator->validateAddPromoForm($promoArr, $this->_faraoAdmin);
        try {
            $promoToAdd = $this->createPromo($promoArr);
        } catch (DomainModelException $ex) {
            return 'admin/promo_manager_add.php';
        }
        if (array_key_exists('hasAnError', $promoFormData) && $promoFormData['hasAnError'] === 'error') {
            return 'admin/promo_manager_add.php';
        }
        try {
            $this->_faraoAdmin->addPromo($promoToAdd);
            return 'adminPromoRedirect';
        } catch (ServiceException $ex) {
            $this->_errorController->goToErrorPage($ex);
        }
    }

    public function updatePromo() {
        try {
            $promoArr = $this->getPromoArr();
        } catch (Exception $ex) {
            $this->_errorController->goToErrorPage($ex);
        }
        $promoArr['pId'] = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'promoIdField'));
        global $promoFormData;
        $promoFormData = $this->_validator->validateUpdatePromoForm($promoArr, $this->_faraoAdmin);
        try {
            $promoTest = $this->createPromo($promoArr);
        } catch (DomainModelException $ex) {
            return 'admin/promo_manager_update.php';
        }
        if (array_key_exists('extraMessage', $promoFormData)) {
            return 'admin/promo_manager_update.php';
        }
        try {
            $this->_faraoAdmin->updatePromo($promoFormData['promoIdPrevVal'], $promoTest->getTitle(), $promoTest->getText(), $this->_beFormat, $promoTest->getStartStr($this->_beFormat), $promoTest->getEndStr($this->_beFormat), $promoFormData['promoActivePrevVal']);
            return 'adminPromoRedirect';
        } catch (ServiceException $ex) {
            $this->_errorController->goToErrorPage($ex);
        }
    }

    public function setPromoIsActive() {
        $id = $this->_validator->sanitizeInput(filter_input(INPUT_GET, 'promoId'));
        $active = $this->_validator->sanitizeInput(filter_input(INPUT_GET, 'promoActive'));
        $newStart = '';
        $newEnd = '';
        if (isset($_POST['promoStart']) && isset($_POST['promoEnd'])) {
            $newStart = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'promoStart'));
            $newEnd = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'promoEnd'));
            //TODO IMPORTANT noscript validation for dates (start and end)!!!!!! 
        }
        try {
            $this->_faraoAdmin->setPromoActiveType($id, !$active, $this->_beFormat, $newStart, $newEnd);
            return $this->isJson() ? 'admin/jsondatahandler.php' : 'adminPromoRedirect';
        } catch (Exception $ex) {
            $this->_errorController->goToErrorPage($ex);
        }
    }

    private function getPromoArr() {
        if (isset($_POST['promoTitle']) && isset($_POST['promoText']) && isset($_POST['promoStart']) && isset($_POST['promoEnd'])) {
            $title = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'promoTitle'));
            $text = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'promoText'));
            $start = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'promoStart'));
            $end = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'promoEnd'));           
            $isActive = isset($_POST['promoActive']) ? ($this->_validator->sanitizeInput(filter_input(INPUT_POST, 'promoActive')) === 'Ja' ? 1 : 0) : 1;
        } else {
            throw new ControllerException('post fields not set! ', NULL);
        }
        $promoArr = array(
            'promoTitle' => $title,
            'promoText' => $text,
            'promoStart' => $start,
            'promoEnd' => $end,
            'promoFormat' => $this->_beFormat,
            'promoActive' => $isActive
        );
        return $promoArr;
    }

    private function createPromo($promoArr) {
        $start = $promoArr['promoStart'];
        $end = $promoArr['promoEnd'];
        $promoToAdd = new Promo(
                $promoArr['promoTitle'], $promoArr['promoText'], $this->_beFormat, $start, $end);
        return $promoToAdd;
    }

    public function updatePromoPage() {
        try {
            $promoId = $this->_validator->sanitizeInput($_GET['promoId']);
            $_GET['updatePromo'] = $this->getPromo($promoId);
            return 'admin/promo_manager_update.php';
        } catch (ServiceException $ex) {
            $this->_errorController->goToErrorPage($ex);
        }
    }

}
