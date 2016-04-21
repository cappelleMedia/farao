<?php

//require_once dirname(__FILE__) . '/../controller/mastercontroller.php';

class GpController extends MasterController {

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

    public function getGuestposts() {
        try {
            return $this->_faraoAdmin->getGuestPosts();
        } catch (Exception $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    public function getLatestGuestposts() {
        try {
            return $this->_faraoAdmin->getLatestGuestposts(5);
        } catch (Exception $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
    }

    public function addGuestpost() {
        $gpArr = $this->getGuestpostArr();
        global $gpFormData;
        $gpFormData = $this->_validator->validateAddGpForm($gpArr, $this->_faraoAdmin);
        try {
            $gpToAdd = $this->createGuestPost($gpArr);
        } catch (DomainModelException $ex) {
            return 'gastenboek.php';
        }
        if (array_key_exists('extraMessage', $gpFormData)) {
            return 'gastenboek.php';
        }
        try {
            $this->_faraoAdmin->addGuestpost($gpToAdd);
        } catch (ServiceException $ex) {
            return $this->_errorController->goToErrorPage($ex);
        }
        header('Location: index.php?action=guestbook');
        exit();
    }

    private function getGuestpostArr() {
        if (isset($_POST['gpName']) && isset($_POST['gpBody'])) {
            $gpName = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'gpName'));
            $gpBody = $this->_validator->sanitizeInput(filter_input(INPUT_POST, 'gpBody'));
        }
        $gpArr = array(
            'gpName' => $gpName,
            'gpBody' => $gpBody
        );
        return $gpArr;
    }

    private function createGuestPost($gpArr) {
        $gpToAdd = new GuestPost(
                $gpArr['gpName'], $gpArr['gpBody'], NULL);
        return $gpToAdd;
    }

}
