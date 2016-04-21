<?php

class UserValidator {

    public function __construct() {
        
    }

    public function validateLoginForm($loginArr, $sysAdmin) {
        $result = $this->getValidationArrayLogin();
        $this->validateloginName($loginArr['loginName'], $result);
        $this->validateLoginPw($loginArr['loginPw'], $result);
        $this->validateLoginValid($loginArr['loginName'], $loginArr['loginPw'], $result, $sysAdmin);
        return $result;
    }

    public function validatePwChangeForm($pwArr, &$sysAdmin) {
        $result = $this->getValidationArrayPw();
        $this->validatePwOld($pwArr['pwOld'], $pwArr['username'], $result, $sysAdmin);
        $this->validatePwNew($pwArr['pwNew'], $result);
        $this->validatePwNewRepeat($pwArr['pwNew'], $pwArr['pwNewRepeat'], $result);
        return $result;
    }

    private function getValidationArrayPw() {
        $validationArray = array(
            'pwOldState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'pwNewState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'pwNewRepeatState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            )
        );
        return $validationArray;
    }

    private function validatePwOld($pwOld, $loginName, &$result, $sysAdmin) {
        $result['pwOldState']['errorClass'] = 'has-success';
        $result['pwOldState']['errorMessage'] = '';
        if (!(trim($pwOld))) {
            $result['pwOldState']['errorClass'] = 'has-error';
            $result['pwOldState']['errorMessage'] = 'Oud paswoord is een verplicht veld';
        }
        try {
            $user = $sysAdmin->getAuthenticatedUser($loginName, $pwOld);
        } catch (ServiceException $ex) {
            $result['pwOldState']['errorClass'] = 'has-error';
            $result['pwOldState']['errorMessage'] = 'Foute paswoord voor deze admin';
        }
    }

    private function validatePwNewRepeat($pwNew, $pwNewrepeat, &$result) {
        $result['pwNewRepeatState']['errorMessage'] = '';
        if ($pwNew !== $pwNewrepeat) {
            $result['pwNewRepeatState']['errorClass'] = 'has-error';
            $result['pwNewRepeatState']['errorMessage'] = 'Het herhaalde paswoord kwam niet overeen met het nieuwe paswoord.';
        }
    }

    private function validatePwNew($pwNew, &$result) {
        $result['pwNewState']['errorClass'] = 'has-success';
        $result['pwNewState']['errorMessage'] = '';
        $result['pwNewState']['prevVal'] = $pwNew;
        if (!(trim($pwNew))) {
            $result['pwNewState']['errorClass'] = 'has-error';
            $result['pwNewState']['errorMessage'] = 'Paswoord is een verplicht veld';
        } else {
            $uppercase = preg_match('@[A-Z]@', $pwNew);
            $lowercase = preg_match('@[a-z]@', $pwNew);
            $number = preg_match('@[0-9]@', $pwNew);
            if (!$uppercase) {
                $result['pwNewState']['errorClass'] = 'has-error';
                $result['pwNewState']['errorMessage'] = 'Een paswoord moet minstens 1 hoofdletter bevatten.<br>';
            } else if (!$lowercase) {
                $result['pwNewState']['errorClass'] = 'has-error';
                $result['pwNewState']['errorMessage'] = 'Een paswoord moet minstens 1 kleine letter bevatten.<br>';
            } else if (!$number) {
                $result['pwNewState']['errorClass'] = 'has-error';
                $result['pwNewState']['errorMessage'] = 'Een paswoord moet minstens 1 cijfer bevatten.<br>';
            }
        }
    }

    private function getValidationArrayLogin() {
        $validationArray = array(
            'loginNameState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'loginPwState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            )
        );
        return $validationArray;
    }

    private function validateLoginName($loginName, &$result) {
        $result['loginNameState']['errorClass'] = 'has-success';
        $result['loginNameState']['errorMessage'] = '';
        $result['loginNameState']['prevVal'] = $loginName;
        if (!(trim($loginName))) {
            $result['loginNameState']['errorClass'] = 'has-error';
            $result['loginNameState']['errorMessage'] = 'Gebruikersnaam is een verplicht veld';
        }
    }

    private function validateLoginPw($loginPw, &$result) {
        $result['loginPwState']['errorClass'] = 'has-success';
        $result['loginPwState']['errorMessage'] = '';
        $result['loginPwState']['prevVal'] = $loginPw;
        if (!(trim($loginPw))) {
            $result['loginPwState']['errorClass'] = 'has-error';
            $result['loginPwState']['errorMessage'] = 'Paswoord is een verplicht veld';
        }
    }

    private function validateLoginValid($loginName, $loginPw, &$result, $sysAdmin) {
        try {
            $user = $sysAdmin->getAuthenticatedUser($loginName, $loginPw);
        } catch (ServiceException $ex) {
            $result['extraMessage'] = 'Geen gebruiker met deze gebruikersnaam en paswoord combinatie';
            $result['loginNameState']['errorClass'] = 'has-error';
            $result['loginPwState']['errorClass'] = 'has-error';
        }
    }

}
