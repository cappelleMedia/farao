<!DOCTYPE html>
<html lang="nl">

    <head>
        <title>Farao admin portaal | portaal</title>
        <?php
        include dirname(__FILE__) . '/includers/admin-header.php';
        global $changePwData;
        ?>     
    </head>

    <body>

        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <?php
                $page = 'portal';
                include dirname(__FILE__) . '/admin_menu.php';
                ?>
            </nav>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Portaal</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-5">
                            <div class="panel panel-red portal-panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Paswoord aanpassen</h3>
                                </div>
                                <div class="panel-body">
                                    <form id="pwChangeForm" method="post" action="index.php?action=admin_updatePw" autocomplete="off" class="">
                                        <fieldset>
                                            <?php
                                            if (isset($changePwData) && !empty($changePwData)) {
                                                if (array_key_exists('extraMessage', $changePwData)) {
                                                    echo '<div class="alert alert-danger">';
                                                    echo $changePwData['extraMessage'];
                                                    echo '</div>';
                                                }
                                            }
                                            ?>
                                            <div class="form-group has-feedback <?php echo empty($changePwData) ? '' : $changePwData['pwOldState']['errorClass']; ?>">
                                                <label for="pwOld">
                                                    Oud paswoord
                                                </label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="fa fa-unlock"></i></span>
                                                    <input type="password" class="form-control"
                                                           id="pwOld"
                                                           name="pwOld"
                                                           placeholder="Vul hier je oud paswoord in"  autofocus required>                                                          
                                                </div>
                                                <?php
                                                if (!empty($changePwData)) {
                                                    if ($changePwData['pwOldState']['errorClass'] === 'has-error') {
                                                        echo '<span id="pwOldErrorIcon" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                                                        echo '<span id="pwOldError" class="sr-only">(error)</span>';
                                                        echo '<span id="pwOldErrorText" class="text-danger">' . $changePwData['pwOldState']['errorMessage'] . '</span>';
                                                    } else {
                                                        echo '<span id="pwOldSuccessIcon" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                                                        echo '<span id="pwOldSuccess" class="sr-only">(success)</span>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <div class="form-group has-feedback <?php echo empty($changePwData) ? '' : $changePwData['pwNewState']['errorClass']; ?>">
                                                <label for="pwNew">
                                                    Nieuw paswoord
                                                </label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                                    <input type="password" class="form-control"
                                                           id="pwNew"
                                                           name="pwNew" value="<?php echo empty($changePwData) ? '' : $changePwData['pwNewState']['prevVal']; ?>"
                                                           placeholder="Vul hier je nieuw paswoord in" required>                                                           
                                                </div>
                                                <?php
                                                if (!empty($changePwData)) {
                                                    if ($changePwData['pwNewState']['errorClass'] === 'has-error') {
                                                        echo '<span id="pwNewErrorIcon" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                                                        echo '<span id="pwNewError" class="sr-only">(error)</span>';
                                                        echo '<span id="pwNewErrortext" class="text-danger">' . $changePwData['pwNewState']['errorMessage'] . '</span>';
                                                    } else {
                                                        echo '<span id="pwNewSuccessIcon" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
                                                        echo '<span id="pwNewSuccess" class="sr-only">(success)</span>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <div id="pwRepeatHolder" class="form-group has-feedback <?php echo empty($changePwData) ? '' : $changePwData['pwNewRepeatState']['errorClass']; ?>">
                                                <label for="pwNewRepeat">
                                                    Nieuw paswoord herhalen
                                                </label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                                                    <input type="password" class="form-control"
                                                           id="pwNewRepeat"
                                                           name="pwNewRepeat"
                                                           placeholder="Vul hier je nieuw paswoord opnieuw in" required>
                                                </div>
                                                <?php
                                                if (!empty($changePwData)) {
                                                    if ($changePwData['pwNewRepeatState']['errorClass'] === 'has-error') {
                                                        echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                                                        echo '<span id="inputPwNewRepeatError" class="sr-only">(error)</span>';
                                                        echo '<span class="text-danger">' . $changePwData['pwNewRepeatState']['errorMessage'] . '</span>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <div class="pull-right">                                        
                                                <button type="submit" class="btn btn-danger" id="pwChangeButton">Wijzigen</button>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-offset-1 col-md-5">
                            <div class="panel panel-primary portal-panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Laatste reacties</h3>
                                </div>
                                <div class="panel-body">
                                    <div id="posts">
                                        <?php
                                        $guestposts = $this->getGpController()->getLatestGuestposts();
                                        foreach ($guestposts as $key => $guestPost) {
                                            ?>                                                               
                                            <?php
                                            echo $guestPost->getBody();
                                            ?>
                                            <h4 class="">
                                                - <?php echo $guestPost->getName() ?><small class="date"> <?php echo $guestPost->getDateTimeStr() ?></small>
                                                <!--(24/01/2015, 13:05)-->
                                            </h4>
                                            <hr>
                                        <?php } ?>  
                                    </div>
                                    <a style="margin-right: 10px;" class="pull-right" href="index.php?action=guestbook">Alle reacties bekijken <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->
        <?php
        include dirname(__FILE__) . '/../includers/scripts.php';
        ?>
        <?php
        include dirname(__FILE__) . '/includers/admin-scripts.php';
        ?>
        <script>
            $(document).ready(function () {
                disableButton('pwChangeButton');
                $('#posts').mCustomScrollbar({
                    theme: 'dark',
                    scrollButtons: {
                        enable: true
                    }
                });
            });
        </script>
    </body>

</html>
