<?php

//require_once dirname(__FILE__) . '/../controller/mastercontroller.php';

class UserController extends MasterController {

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

    private function getLoginArr() {
        if (isset($_POST['loginName']) && isset($_POST['loginPw'])) {
            $loginName = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'loginName'));
            $loginPw = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'loginPw'));
        }
        $loginArr = array(
            'loginName' => $loginName,
            'loginPw' => $loginPw
        );
        return $loginArr;
    }

    public function login() {
        $userArr = $this->getLoginArr();
        global $loginFormData;
        $loginFormData = $this->_validator->validateLoginForm($userArr, $this->_faraoAdmin);
        if ($loginFormData['loginNameState']['errorClass'] === 'has-error' || $loginFormData['loginPwState']['errorClass'] === 'has-error') {
            return 'admin/admin_loginPage.php';
        }
        if (array_key_exists('extraMessage', $loginFormData)) {
            return 'admin/admin_loginPage.php';
        }
        try {
            $user = $this->checkPWCorrect($userArr['loginName'], $userArr['loginPw']);
        } catch (ServiceException $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
        parent::setSessionAttr('current_user', $user);
        header('Location: index.php?action=adminPortal');
        exit();
    }

    public function logout() {
        parent::startSession();
        session_unset();
        session_destroy();
        header('Location: index.php?action=adminPortal');
        exit();
    }

    private function checkPWCorrect($username, $password) {
        try {
            return $this->_faraoAdmin->getAuthenticatedUser($username, $password);
        } catch (ServiceException $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    //admin stuff
    public function updateUserPw() {
        $pwArr = $this->getPwArr();
        global $changePwData;
        $changePwData = $this->_validator->validatePwChangeForm($pwArr, $this->_faraoAdmin);
        if ($changePwData['pwOldState']['errorClass'] === 'has-error' ||
                $changePwData['pwNewState']['errorClass'] === 'has-error' ||
                $changePwData['pwNewRepeatState']['errorClass'] === 'has-error') {
            return 'admin/admin_portal.php';
        }
        try {
            $userId = $this->_faraoAdmin->getUserByUsername($pwArr['username'])->getId();
            $this->_faraoAdmin->updateUserPw($userId, $pwArr['pwOld'], $pwArr['pwNew']);
            return 'adminHomeRedirect';
        } catch (Exception $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    private function getPwArr() {
        if (isset($_POST['pwOld']) && isset($_POST['pwNew']) && isset($_POST['pwNewRepeat'])) {
            $pwOld = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'pwOld'));
            $pwNew = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'pwNew'));
            $pwNewRepeat = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'pwNewRepeat'));
        }
//        session_write_close();

        $username = parent::getSessionAttr('current_user')->getUser_name();
        $loginArr = array(
            'username' => $username,
            'pwOld' => $pwOld,
            'pwNew' => $pwNew,
            'pwNewRepeat' => $pwNewRepeat
        );
        return $loginArr;
    }

}
