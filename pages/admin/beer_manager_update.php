<!DOCTYPE html>
<!-- page vars -->
<?php
global $beerFormData;
$updateId = empty($beerFormData) ? $this->getValidator()->sanitizeInput($_GET['beerId']) : $beerFormData['beerIdPrevVal'];
$beer = isset($_GET['updateBeer']) ? $_GET['updateBeer'] : NULL;
?>
<html lang="nl">
    <head>
        <!--TODO use JS for validation -->
        <?php
        include dirname(__FILE__) . '/includers/admin-header.php';
        ?>
        <title>Bier manager|update</title>
    </head>
    <body>
        <div class="wrapper">
            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <?php
                $page = 'beermgr';
                include dirname(__FILE__) . '/admin_menu.php';
                ?>
            </nav>
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">                        
                        <form method="post" action="index.php?action=admin_updateBeer">
                            <fieldset>
                                <legend>Bier aanpassen</legend>
                                <?php
                                if (isset($beerFormData) && !empty($beerFormData)) {
                                    if (array_key_exists('extraMessage', $beerFormData)) {
                                        echo '<div class="alert alert-danger">';
                                        echo $beerFormData['extraMessage'];
                                        echo '</div>';
                                    }
                                }
                                ?>
                                <div class="form-goup">
                                    <label for="beerIdField">
                                        ID
                                    </label>
                                    <input type="number" class="form-control" id="beerIdField" name="beerIdField" 
                                           readonly
                                           value="<?php
                                           if (!empty($beerFormData) && array_key_exists('beerIdPrevVal', $beerFormData)) {
                                               echo $beerFormData['beerIdPrevVal'];
                                           } else {
                                               echo $beer->getId();
                                           }
                                           ?>">
                                </div>
                                <div class="form-group has-feedback <?php echo empty($beerFormData) ? '' : $beerFormData['beerNameState']['errorClass']; ?>">
                                    <label for="beerName">
                                        Bier naam
                                    </label>
                                    <input autofocus type="text" class="form-control"
                                           id="beerName"
                                           name="beerName" value="<?php echo empty($beerFormData) ? $beer->getName() : $beerFormData['beerNameState']['prevVal']; ?>"
                                           placeholder="Vul hier de naam van het bier in" required>
                                           <?php
                                           if (!empty($beerFormData)) {
                                               if ($beerFormData['beerNameState']['errorClass'] === 'has-error') {
                                                   echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                                                   echo '<span id="inputBeerNameError" class="sr-only">(error)</span>';
                                                   echo '<span class="text-danger">' . $beerFormData['beerNameState']['errorMessage'] . '</span>';
                                               } else {
                                                   echo '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                                                   echo '<span id="inputBeerNameSuccess" class="sr-only">(success)</span>';
                                               }
                                           }
                                           ?>
                                </div>

                                <div class="form-group has-feedback <?php echo empty($beerFormData) ? '' : $beerFormData['beerDegreesState']['errorClass']; ?>">
                                    <label for="beerDeg">
                                        Bier aantal graden
                                    </label>
                                    <input type="number" step="0.1" class="form-control" id="beerDeg"
                                           name="beerDeg" value="<?php echo empty($beerFormData) ? $beer->getDegrees() : $beerFormData['beerDegreesState']['prevVal']; ?>"
                                           placeholder="Vul hier het aantal graden van het bier in" required>
                                           <?php
                                           if (!empty($beerFormData)) {
                                               if ($beerFormData['beerDegreesState']['errorClass'] === 'has-error') {
                                                   echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                                                   echo '<span id="inputBeerNameError" class="sr-only">(error)</span>';
                                                   echo '<span class="text-danger">' . $beerFormData['beerDegreesState']['errorMessage'] . '</span>';
                                               } else {
                                                   echo '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                                                   echo '<span id="inputBeerNameSuccess" class="sr-only">(success)</span>';
                                               }
                                           }
                                           ?>
                                </div>

                                <div class="form-group has-feedback <?php echo empty($beerFormData) ? '' : $beerFormData['breweryNameState']['errorClass']; ?>">
                                    <label for="brewName">
                                        Brouwerij naam
                                    </label>
                                    <input type="text" class="form-control" id="brewName"
                                           value ="<?php echo empty($beerFormData) ? $beer->getBrewery_name() : $beerFormData['breweryNameState']['prevVal']; ?>"
                                           name="brewName" placeholder="Vul hier de naam van de brouwer in"
                                           required>
                                           <?php
                                           if (!empty($beerFormData)) {
                                               if ($beerFormData['breweryNameState']['errorClass'] === 'has-error') {
                                                   echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                                                   echo '<span id="inputBeerNameError" class="sr-only">(error)</span>';
                                                   echo '<span class="text-danger">' . $beerFormData['breweryNameState']['errorMessage'] . '</span>';
                                               } else {
                                                   echo '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                                                   echo '<span id="inputBeerNameSuccess" class="sr-only">(success)</span>';
                                               }
                                           }
                                           ?>
                                </div>

                                <div class="form-group has-feedback <?php echo empty($beerFormData) ? '' : $beerFormData['breweryUrlState']['errorClass']; ?>">
                                    <label for="brewUrl">
                                        Brouwerij site
                                    </label>
                                    <input type="text" class="form-control" id="brewUrl"
                                           value ="<?php echo empty($beerFormData) ? $beer->getBrewery_url() : $beerFormData['breweryUrlState']['prevVal']; ?>"
                                           name="brewUrl" placeholder="Vul hier de website van de brouwer in"
                                           required>
                                           <?php
                                           if (!empty($beerFormData)) {
                                               if ($beerFormData['breweryUrlState']['errorClass'] === 'has-error') {
                                                   echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                                                   echo '<span id="inputBeerNameError" class="sr-only">(error)</span>';
                                                   echo '<span class="text-danger">' . $beerFormData['breweryUrlState']['errorMessage'] . '</span>';
                                               } else {
                                                   echo '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                                                   echo '<span id="inputBeerNameSuccess" class="sr-only">(success)</span>';
                                               }
                                           }
                                           ?>
                                </div>                            

                                <?php
                                if (!empty($beerFormData)) {
                                    $bType = $beerFormData['beerTypeState']['prevVal'];
                                } else if (isset($beer)) {
                                    $bType = $beer->getType();
                                }
                                ?>

                                <div class="form-group has-feedback <?php echo empty($beerFormData) ? '' : $beerFormData['beerTypeState']['errorClass']; ?>">
                                    <label for="beerType">
                                        Bier type
                                    </label>
                                    <select class="form-control" id="beerType"
                                            name="beerType" required>
                                        <optgroup label="-- kies het bier type">
                                            <option <?php echo $bType == 'fles' ? 'selected' : '' ?>>
                                                fles
                                            </option>
                                            <option <?php echo $bType == 'vat' ? 'selected' : '' ?>>
                                                vat
                                            </option>
                                            <option <?php echo $bType == 'trappist' ? 'selected' : '' ?>>
                                                trappist
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>

                                <?php
                                if (!empty($beerFormData)) {
                                    $bAvail = $beerFormData['beerAvailableState']['prevVal'] ? 'JA' : 'NEE';
                                } else
                                if (isset($beer)) {
                                    $bAvail = $beer->getAvailable() ? 'JA' : 'NEE';
                                }
                                ?>

                                <div class="form-group has-feedback <?php echo empty($beerFormData) ? '' : $beerFormData['beerAvailableState']['errorClass']; ?>">
                                    <label for="beerAvailable">
                                        Bier beschikbaar
                                    </label>
                                    <select class="form-control" id="beerAvailable"
                                            name="beerAvailable" required>
                                        <optgroup label="-- kies ja/nee">
                                            <option <?php echo $bAvail == 'JA' ? 'selected' : '' ?>>
                                                JA
                                            </option>
                                            <option <?php echo $bAvail == 'NEE' ? 'selected' : '' ?>>
                                                NEE
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>                            

                            </fieldset>
                            <button type="submit" class="btn btn-success col-lg-offset-11 col-lg-1" id="submitButton">Klaar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include dirname(__FILE__) . '/../includers/scripts.php';
        include dirname(__FILE__) . '/includers/admin-scripts.php';
        ?>
    </body>
</html>