<?php

class GpValidator {

    public function __construct() {
        
    }

     public function validateAddGpForm($gpArr, $sysAdmin) {
        $result = $this->getValidationArrayGp();
        $this->validateGpName($gpArr['gpName'], $result);
        $this->validateGpBody($gpArr['gpBody'], $result);
        return $result;
    }
    
    private function getValidationArrayGp() {
        $validationArray = array(
            'gpNameState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'gpBodyeState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            )
        );
        return $validationArray;
    }

    private function validateGpName($gpName, &$result) {
        $result['gpNameState']['errorClass'] = 'has-success';
        $result['gpNameState']['errorMessage'] = '';
        $result['gpNameState']['prevVal'] = $gpName;
        if (!(trim($gpName))) {
            $result['gpNameState']['prevVal'] = 'Annoniem';
        }
    }

    private function validateGpBody($gpBody, &$result) {
        $result['gpBodyState']['errorClass'] = 'has-success';
        $result['gpBodyState']['errorMessage'] = '';
        $result['gpBodyState']['prevVal'] = $gpBody;
        if (!(trim($gpBody))) {
            $result['gpBodyState']['errorClass'] = 'has-error';
            $result['gpBodyState']['errorMessage'] = 'Boodschap is een verplicht veld';
        }
    }

}
