<?php

class PromoValidator {

    public function __construct() {
        
    }

    //FIXME make a method to generate the array!
    private function getValidationArrayPromo() {
        $validationArray = array(
            'promoTitleState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'promoTextState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'promoStartState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'promoEndState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            )
        );
        return $validationArray;
    }

    //FIXME use this method for a parent validator class to access in all validators
    private function getResultStrings() {
        return array('errorClass', 'errorMessage', 'prevVal');
    }

    private function validateActive($active, &$result) {
        $result['promoActivePrevVal'] = $active;
    }
    private function validateId($id, &$result) {
        $result['promoIdPrevVal'] = $id;
    }

    //FIXME use this method for a parent validator class to access in all validators
    private function validateNotEmpty($field, $name, $val, &$result) {
        $keys = $this->getResultStrings();
        $result[$field][$keys[0]] = 'has-success';
        $result[$field][$keys[1]] = '';
        $result[$field][$keys[2]] = $val;
        if (!(trim($val))) {
            $result['hasAnError'] = 'error';
            $result[$field][$keys[0]] = 'has-error';
            $result[$field][$keys[1]] = $name . ' is een verplicht veld';
        }
    }

    private function validateDates($start, $end, &$result) {
        $keys = $this->getResultStrings();
        if ($this->validateStart($start, $result) && $this->validateEnd($end, $result)) {
            $startF = DateTime::createFromFormat('d/m/Y', $start);
            $endF = DateTime::createFromFormat('d/m/Y', $end);
            if ($endF < $startF) {
                $result['hasAnError'] = 'error';
                $result['promoStartState'][$keys[0]] = 'has-error';
                $result['promoEndState'][$keys[0]] = 'has-error';
                $result['promoStartState'][$keys[1]] = 'Zorg ervoor dat de start datum eerder is dan de eind datum';
                $result['promoEndState'][$keys[1]] = 'Zorg ervoor dat de eind datum later is dan de start datum';
            }
        }
    }

    private function validateStart($start, &$result) {
        $field = 'promoStartState';
        $keys = $this->getResultStrings();
        $result[$field][$keys[0]] = 'has-success';
        $result[$field][$keys[1]] = '';
        $result[$field][$keys[2]] = $start;
        if (!$this->validateDate($start)) {
            $result['hasAnError'] = 'error';
            $result[$field][$keys[0]] = 'has-error';
            $result[$field][$keys[1]] = 'Start was geen valide datum';
            return false;
        }
        return true;
    }

    private function validateEnd($end, &$result) {
        $field = 'promoEndState';
        $keys = $this->getResultStrings();
        $result[$field][$keys[0]] = 'has-success';
        $result[$field][$keys[1]] = '';
        $result[$field][$keys[2]] = $end;

        if (!$this->validateDate($end)) {
            $result['hasAnError'] = 'error';
            $result[$field][$keys[0]] = 'has-error';
            $result[$field][$keys[1]] = 'Einde was geen valide datum';
            return false;
        }
        return true;
    }

    private function validateDate($date) {
        $d = DateTime::createFromFormat('d/m/Y', $date);
        return $d && $d->format('d/m/Y') == $date;
    }

    public function validateUpdatePromoForm($promoArr, $sysAdmin) {
        $result = $this->getValidationArrayPromo();
        $this->validateId($promoArr['pId'], $result);
        $this->validateActive($promoArr['promoActive'], $result);
        $this->fullvalidation($promoArr, $result);
        return $result;
    }

    public function validateAddPromoForm($promoArr, $sysAdmin) {
        $result = $this->getValidationArrayPromo();
        $this->fullvalidation($promoArr, $result);
        return $result;
    }

    private function fullvalidation($promoArr, &$result) {
        $this->validateNotEmpty('promoTitleState', 'Promo', $promoArr['promoTitle'], $result);
        $this->validateNotEmpty('promoTextState', 'Promo', $promoArr['promoText'], $result);
        $this->validateDates($promoArr['promoStart'], $promoArr['promoEnd'], $result);
    }

}
