<?php

//require_once 'uservalidator.php';
//require_once 'beervalidator.php';
//require_once 'gpvalidator.php';

class FormValidator {

    private $_userValidator;
    private $_beerValidator;
    private $_gpValidator;
    private $_promoValidator;

    public function __construct() {
        $this->init();
    }

    private function init() {
        $this->_userValidator = new UserValidator();
        $this->_beerValidator = new BeerValidator();
        $this->_gpValidator = new GpValidator();
        $this->_promoValidator = new PromoValidator();
    }

    public function isValidURL($url) {
        return $this->_beerValidator->isValidURL($url);
    }

    public function sanitizeInput($input) {
        if (isset($input)) {
            $input = stripslashes($input);
            $input = htmlentities($input);
            $input = strip_tags($input);
            return $input;
        }
    }

    //beer stuff  
    public function validateUpdateBeerform($beerArr, &$sysAdmin) {
        return $this->_beerValidator->validateUpdateBeerform($beerArr, $sysAdmin);
    }

    public function validateAddBeerForm($beerArr, &$sysAdmin) {
        return $this->_beerValidator->validateAddBeerForm($beerArr, $sysAdmin);
    }

    //guestpost stuff   
    public function validateAddGpForm($gpArr, &$sysAdmin) {
        return $this->_gpValidator->validateAddGpForm($gpArr, $sysAdmin);
    }

    //login stuff
    public function validateLoginForm($loginArr, &$sysAdmin) {
        return $this->_userValidator->validateLoginForm($loginArr, $sysAdmin);
    }

    public function validatePwChangeForm($pwArr, &$sysAdmin) {
        return $this->_userValidator->validatePwChangeForm($pwArr, $sysAdmin);
    }

    //promo stuff
    public function validateUpdatePromoForm($promoArr, &$sysAdmin) {
        return $this->_promoValidator->validateUpdatePromoForm($promoArr, $sysAdmin);
    }

    public function validateAddPromoForm($promoArr, &$sysAdmin) {
        $result = $this->_promoValidator->validateAddPromoForm($promoArr, $sysAdmin);
        return $result;
    }

}
