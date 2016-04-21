<?php

class BeerValidator {

    public function __construct() {
        
    }

    public function validateUpdateBeerform($beerArr, $sysAdmin) {
        $result = $this->getValidationArrayBeer();
        $this->validateId($beerArr['bId'], $result);
        $this->validateBeerName($beerArr['beerName'], $result);
        $this->validateBeerDegrees($beerArr['beerDeg'], $result);
        $this->validateBreweryName($beerArr['brewName'], $result);
        $this->validateBreweryUrl($beerArr['brewUrl'], $result);
        $this->validateBeerAvailable($beerArr['beerAvailable'], $result);
        $this->validateBeerType($beerArr['beerType'], $result);
        if ($this->validateContainsNameUpdate($beerArr['beerName'], $beerArr['beerType'], $beerArr['bId'], $sysAdmin, $result)) {
            $result['extraMessage'] = 'Je kan een bier maar 1 keer toevoegen met eenzelfde naam en type ' .
                    '(1 keer dezelfde naam per type dus)' .
                    '<br>Probeer eventueel de naam of het type te veranderen of kijk even na of je het bier misschien al hebt toegevoegd';
            $result['beerNameState']['errorClass'] = 'has-error';
            $result['beerTypeState']['errorClass'] = 'has-error';
        }

        return $result;
    }

    public function validateAddBeerForm($beerArr, $sysAdmin) {
        $result = $this->getValidationArrayBeer();
        $this->validateBeerName($beerArr['beerName'], $result);
        $this->validateBeerDegrees($beerArr['beerDeg'], $result);
        $this->validateBreweryName($beerArr['brewName'], $result);
        $this->validateBreweryUrl($beerArr['brewUrl'], $result);
        $this->validateBeerAvailable($beerArr['beerAvailable'], $result);
        $this->validateBeerType($beerArr['beerType'], $result);
        if ($this->validateContainsName($beerArr['beerName'], $beerArr['beerType'], $sysAdmin, $result)) {
            $result['extraMessage'] = 'Je kan een bier maar 1 keer toevoegen met eenzelfde naam en type ' .
                    '(1 keer dezelfde naam per type dus)' .
                    '<br>Probeer eventueel de naam of het type te veranderen of kijk even na of je het bier misschien al hebt toegevoegd';
            $result['beerNameState']['errorClass'] = 'has-error';
            $result['beerTypeState']['errorClass'] = 'has-error';
        }
        return $result;
    }

    private function getValidationArrayBeer() {
        $validationArray = array(
            'beerNameState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'beerDegreesState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'breweryNameState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'breweryUrlState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'beerAvailableState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            ), 'beerTypeState' => array(
                'errorClass' => '',
                'errorMessage' => '',
                'prevVal' => ''
            )
        );
        return $validationArray;
    }

    private function validateContainsNameUpdate($beerName, $beerType, $beerId, $sysAdmin, &$result) {
        //this way it's possible to update a beer without having to change the name and still not being able to add doubles
        if ($this->validateContainsName($beerName, $beerType, $sysAdmin, $result)) {
            $withName = $sysAdmin->getBeersWithName($beerName);
            foreach ($withName as $beer) {
                if ($beer->getId() != $beerId && $beer->getType() == $beerType) {
                    return true;
                }
            }
        }
        return false;
    }

    private function validateContainsName($beerName, $beerType, $sysAdmin, &$result) {
        if ($sysAdmin->containsBeerNameSameType($beerName, $beerType)) {
            return true;
        }
        return false;
    }

    private function validateId($id, &$result) {
        $result['beerIdPrevVal'] = $id;
    }

    private function validateBeerName($beerName, &$result) {
        $result['beerNameState']['errorClass'] = 'has-success';
        $result['beerNameState']['errorMessage'] = '';
        $result['beerNameState']['prevVal'] = $beerName;
        if (!(trim($beerName))) {
            $result['beerNameState']['errorClass'] = 'has-error';
            $result['beerNameState']['errorMessage'] = 'biernaam is een verplicht veld';
        }
    }

    private function validateBeerDegrees($beerDegrees, &$result) {
        $result['beerDegreesState']['errorClass'] = 'has-success';
        $result['beerDegreesState']['errorMessage'] = '';
        $result['beerDegreesState']['prevVal'] = $beerDegrees;
        if (!(trim($beerDegrees))) {
            $result['beerDegreesState']['errorClass'] = 'has-error';
            $result['beerDegreesState']['errorMessage'] = 'Het aantal graden moet een ingevuld zijn ';
        }
        if (!is_numeric($beerDegrees)) {
            $result['beerDegreesState']['errorClass'] = 'has-error';
            $result['beerDegreesState']['errorMessage'] .= 'Het aantal graden moet een getal zijn ';
        }
        if ($beerDegrees < 0) {
            $result['beerDegreesState']['errorClass'] = 'has-error';
            $result['beerDegreesState']['errorMessage'] .= 'Het aantal graden kan niet negatief zijn';
        }
    }

    private function validateBreweryName($breweryName, &$result) {
        $result['breweryNameState']['errorClass'] = 'has-success';
        $result['breweryNameState']['errorMessage'] = '';
        $result['breweryNameState']['prevVal'] = $breweryName;
        if (!(trim($breweryName))) {
            $result['breweryNameState']['errorClass'] = 'has-error';
            $result['breweryNameState']['errorMessage'] = 'Brouwerijnaam is een verplicht veld';
        }
    }

    private function validateBreweryUrl($breweryUrl, &$result) {
        $result['breweryUrlState']['errorClass'] = 'has-success';
        $result['breweryUrlState']['errorMessage'] = '';
        $result['breweryUrlState']['prevVal'] = $breweryUrl;
        if (!(trim($breweryUrl))) {
            $result['breweryUrlState']['errorClass'] = 'has-error';
            $result['breweryUrlState']['errorMessage'] = 'De url van de brouwerij is een verplicht veld';
        } else if (!$this->isValidURL($breweryUrl)) {
            $result['breweryUrlState']['errorClass'] = 'has-error';
            $result['breweryUrlState']['errorMessage'] = 'De ingevoerde url is geen geldige website url<br>Een url moet deze vorm hebben: \'www.website.be\'';
        }
    }

    private function validateBeerAvailable($beerAvailable, &$result) {
        $result['beerAvailableState']['errorClass'] = 'has-success';
        $result['beerAvailableState']['errorMessage'] = '';
        $result['beerAvailableState']['prevVal'] = $beerAvailable;
    }

    private function validateBeerType($beerType, &$result) {
        $result['beerTypeState']['errorClass'] = 'has-success';
        $result['beerTypeState']['errorMessage'] = '';
        $result['beerTypeState']['prevVal'] = $beerType;
        $_types = array('fles', 'vat', 'trappist');
        if (!(trim($beerType))) {
            $result['beerTypeState']['errorClass'] = 'has-error';
            $result['beerTypeState']['errorMessage'] = 'Biertype is een verplicht veld';
        } else if (!in_array($beerType, $_types)) {
            $result['beerTypeState']['errorClass'] = 'has-error';
            $result['beerTypeState']['errorMessage'] = 'Biertype kan enkel \'fles\', \'vat\' of \'trappist\' zijn';
        }
    }
    
    public function isValidURL($url) {
        if ($url) {
            if (strpos($url, 'http') === FALSE) {
                $url = 'http://' . $url;
            }
            //regex src = https://mathiasbynens.be/demo/url-regex; @diegoperini
            if (!preg_match('_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS', $url)) {
                return false;
            }
            /* $header = @get_headers($url); //safe to suppress warning here because when fail just need false
              if(!$header){
              return false;
              }
              if (strpos($header[0], '404')) {
              return false;
              } FIXME this is really slowing stuff down! */
            return true;
        }
        return false;
    }

}
