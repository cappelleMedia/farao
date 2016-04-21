<!DOCTYPE html>
<html lang="nl">

    <head>
        <title>Farao admin portaal | login</title>
        <?php
        include dirname(__FILE__) . '/includers/admin-header.php';
        global $loginFormData
        ?>             
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-offset-4 col-md-4 col-xs-offset-2 col-xs-8">

                    <div class="panel login-panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Aanmelden voor het admin portaal</h3>
                        </div>
                        <div class="panel-body">

                            <form id="loginForm" method="post" action="index.php?action=login" autocomplete="off" class="">
                                <fieldset>
                                    <?php
                                    if (isset($loginFormData) && !empty($loginFormData)) {
                                        if (array_key_exists('extraMessage', $loginFormData)) {
                                            echo '<div class="alert alert-danger">';
                                            echo $loginFormData['extraMessage'];
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                    <div class="form-group has-feedback <?php echo empty($loginFormData) ? '' : $loginFormData['loginNameState']['errorClass']; ?>">
                                        <label for="loginName">
                                            Gebruikersnaam
                                        </label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control"
                                                   id="loginName"
                                                   name="loginName" value="<?php echo empty($loginFormData) ? '' : $loginFormData['loginNameState']['prevVal']; ?>"
                                                   placeholder="Vul hier je gebruikersnaam in"  autofocus required>                                                  
                                        </div>
                                        <?php
                                        if (!empty($loginFormData)) {
                                            if ($loginFormData['loginNameState']['errorClass'] === 'has-error') {
                                                echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                                                echo '<span id="inputLoginNameError" class="sr-only">(error)</span>';
                                                echo '<span class="text-danger">' . $loginFormData['loginNameState']['errorMessage'] . '</span>';
                                            } else {
                                                echo '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                                                echo '<span id="inputLoginNameSuccess" class="sr-only">(success)</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="form-group has-feedback <?php echo empty($loginFormData) ? '' : $loginFormData['loginPwState']['errorClass']; ?>">
                                        <label for="loginPw">
                                            Paswoord
                                        </label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                            <input type="password" class="form-control"
                                                   id="loginPw"
                                                   name="loginPw"
                                                   placeholder="Vul hier je paswoord in" required>                                                  
                                        </div>
                                        <?php
                                        if (!empty($loginFormData)) {
                                            if ($loginFormData['loginPwState']['errorClass'] === 'has-error') {
                                                echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                                                echo '<span id="inputLoginPwError" class="sr-only">(error)</span>';
                                                echo '<span class="text-danger">' . $loginFormData['loginPwState']['errorMessage'] . '</span>';
                                            } else {
                                                echo '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                                                echo '<span id="inputPwSuccess" class="sr-only">(success)</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="pull-right">                                        
                                        <a href="index.php?action=home" class="btn btn-outline btn-danger">Anuleren</a>
                                        <button type="submit" class="btn btn-outline btn-success" id="submitButton">Aanmelden</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include dirname(__FILE__) . '/../includers/scripts.php';
        ?>
        <script>
            $(document).ready(function () {
                $('#loginName').select();
            });
        </script>
    </body>
</html>