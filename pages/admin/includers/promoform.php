<form novalidate id="promoForm" method="post" action="index.php?action=admin_<?php echo $type === 'add' ? 'addNewPromo' : 'updatePromo'; ?>">
    <fieldset>
        <legend><?php echo $type === 'add' ? 'Nieuwe promotie toevoegen' : 'Promotie aanpassen' ?></legend>
        <?php
        if (isset($promoFormData) && !empty($promoFormData)) {
            if (array_key_exists('extraMessage', $promoFormData)) {
                echo '<div class="alert alert-danger">';
                echo $promoFormData['extraMessage'];
                echo '</div>';
            }
        }
        ?>

        <?php
        $idHtml = '';
        if ($type === 'update') {
            $idHtml = '<div class="form-goup has-feedback">' .
                    '<label for="promoIdField"> ' .
                    'ID' .
                    '</label>' .
                    '<input type="number" class="form-control" id="promoIdField" name="promoIdField" readonly ' .
                    'value="';
            if (!empty($promoFormData) && array_key_exists('promoIdPrevVal', $promoFormData)) {
                $idHtml .= $promoFormData['promoIdPrevVal'];
            } else {
                $idHtml.= $promo === null ? '' : $promo->getId();
            }
            $idHtml .= '"></div>';
            echo $idHtml;
        }
        ?>

        <div class="form-group has-feedback <?php echo empty($promoFormData) ? '' : ($promoFormData['promoTitleState']['errorClass']); ?>">
            <label for="promoTitle">
                Promotie titel
            </label>
            <div class="form-group input-group">
                <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                <input autofocus type="text" class="form-control"
                       id="promoTitle"
                       name="promoTitle" value="<?php echo empty($promoFormData) ? ($promo === null ? '' : $promo->getTitle()) : $promoFormData['promoTitleState']['prevVal']; ?>"
                       placeholder="Vul hier de titel van de promotie in" required>
            </div>
            <?php
            if (!empty($promoFormData)) {
                if ($promoFormData['promoTitleState']['errorClass'] === 'has-error') {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="inputPromoNameError" class="sr-only">(error)</span>';
                    echo '<span class="text-danger">' . $promoFormData['promoTitleState']['errorMessage'] . '</span>';
                } else {
                    echo '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="inputPromoNameSuccess" class="sr-only">(success)</span>';
                }
            }
            ?>
        </div>

        <div class="form-group has-feedback <?php echo empty($promoFormData) ? '' : $promoFormData['promoTextState']['errorClass']; ?>">
            <label for="promoText">
                Promotie omschrijving
            </label>
            <div class="form-group input-group">
                <span class="input-group-addon"><i class="fa fa-newspaper-o"></i></span>
                <input type="text" class="form-control" id="promoText"
                       name="promoText" value="<?php echo empty($promoFormData) ? ($promo === null ? '' : $promo->getText()) : $promoFormData['promoTextState']['prevVal']; ?>"
                       placeholder="Vul hier de omschrijving van de promotie" required>
            </div>
            <?php
            if (!empty($promoFormData)) {
                if ($promoFormData['promoTextState']['errorClass'] === 'has-error') {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="inputPromoTextError" class="sr-only">(error)</span>';
                    echo '<span class="text-danger">' . $promoFormData['promoTextState']['errorMessage'] . '</span>';
                } else {
                    echo '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="inputPromoTextSuccess" class="sr-only">(success)</span>';
                }
            }
            ?>
        </div>

        <div class="form-group has-feedback <?php echo empty($promoFormData) ? '' : $promoFormData['promoStartState']['errorClass']; ?>">
            <label for="promoStart">
                Promotie start datum
            </label>
            <div class="form-group input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" id="promoStart"
                       value ="<?php echo empty($promoFormData) ? ($promo === null ? date('d/m/Y', strtotime('now') + 1) : $promo->getStartStr('d/m/Y')) : $promoFormData['promoStartState']['prevVal']; ?>"
                       name="promoStart"
                       required>
            </div>
            <?php
            if (!empty($promoFormData)) {
                if ($promoFormData['promoStartState']['errorClass'] === 'has-error') {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="inputPromoStartError" class="sr-only">(error)</span>';
                    echo '<span class="text-danger">' . $promoFormData['promoStartState']['errorMessage'] . '</span>';
                } else {
                    echo '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="inputPromoStartSuccess" class="sr-only">(success)</span>';
                }
            }
            ?>
        </div>

        <div class="form-group has-feedback <?php echo empty($promoFormData) ? '' : $promoFormData['promoEndState']['errorClass']; ?>">
            <label for="promoEnd">
                Promotie eind datum
            </label>
            <div class="form-group input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" id="promoEnd"
                       value ="<?php echo empty($promoFormData) ? ($promo === null ? date('d/m/Y', strtotime('tomorrow') + 1) : $promo->getEndStr('d/m/Y')) : $promoFormData['promoEndState']['prevVal']; ?>"
                       name="promoEnd"
                       required>
            </div>
            <?php
            if (!empty($promoFormData)) {
                if ($promoFormData['promoEndState']['errorClass'] === 'has-error') {
                    echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="inputPromoEndError" class="sr-only">(error)</span>';
                    echo '<span class="text-danger">' . $promoFormData['promoEndState']['errorMessage'] . '</span>';
                } else {
                    echo '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                    echo '<span id="inputPromoEndSuccess" class="sr-only">(success)</span>';
                }
            }
            ?>
        </div>


        <?php
        $activeHtml = '';
        if ($type === 'update') {
            $activeHtml = '<div class="form-group has-feedback">' .
                    '<label for="promoActive">' .
                    'Actief' .
                    '</label>' .
                    '<select id="promoActive" name="promoActive" class="form-control">' .
                    '<option ';
            if (!empty($promoFormData) && array_key_exists('promoActivePrevVal', $promoFormData)) {
                $activeHtml .= $promoFormData['promoActivePrevVal'] ? 'selected' : '';
            } else {
                $activeHtml.= $promo === null ? '' : $promo->isActive() ? 'selected' : '';
            }
            $activeHtml .= '> Ja</option> <option ';
            if (!empty($promoFormData) && array_key_exists('promoActivePrevVal', $promoFormData)) {
                $activeHtml .= $promoFormData['promoActivePrevVal'] ? 'selected' : '';
            } else {
                $activeHtml.= $promo === null ? '' : $promo->isActive() ? '' : 'selected';
            }
            $activeHtml .= '> Nee </option></select></div>';
            echo $activeHtml;
        }
        ?>

    </fieldset>
    <a href="index.php?action=adminPromoRedirect" class="btn btn-danger col-lg-2">Annuleren</a>
    <button type="submit" class="btn btn-success col-lg-2 pull-right" id="submitButton">Klaar</button>
</form>