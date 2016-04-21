<!DOCTYPE html>
<html lang="nl">
    <head>
        <?php
        include dirname(__FILE__) . '/includers/header.php';
        ?>
        <title>Gastenboek</title>
    </head>
    <body class="green-grid">
        <div class="wrap">
            <header>
                <?php
                $page = 'guestbook';
                include dirname(__FILE__) . '/includers/menu.php';
                ?>
            </header>
            <main role="main" class="first container-fluid">                
                <div class="row">
                    <div class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8">
                        <div class="panel panel-default" >
                            <div class="panel-heading">
                                <h1 class="panel-title">Wat andere van Café Farao vinden</h1>
                            </div>
                            <div class="panel-body" id="posts">                        
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <?php                                
                                        $guestposts = $this->getGpController()->getGuestposts();
                                        foreach ($guestposts as $key => $guestPost) {
                                            ?>                                                               
                                            <?php
                                            echo $guestPost->getBody();
                                            ?>
                                            <h4 class="">
                                                - <?php echo $guestPost->getName()?><small class="date"> <?php echo $guestPost->getDateTimeStr() ?></small>
                                                <!--(24/01/2015, 13:05)-->
                                            </h4>
                                        
                                        <?php } ?>             
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>

                <div id="new-post" class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8">
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title">Wat vind jij van Café Farao?</h1>
                            </div>
                            <div class="panel-body">
                                <form method="post" action="index.php?action=addGp" autocomplete="off">
                                    <fieldset>
                                        <div class="form-group">
                                            <label for="gpName">Naam</label>
                                            <div class="input-group">
                                                <span id="post-name-span" class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="text" class="form-control" id="gpName" name="gpName" placeholder="Vul hier je naam in">
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:none;">
                                            <label for="url">Anti-spam</label>
                                            <div class="input-group">
                                                <span id="post-url-span" class ="input-group-addon">
                                                    <i class="fa fa-warning"></i>
                                                </span>
                                                <input type="text" class="form-control" id="url" name="url" placeholder="Laat dit leeg, anders wordt je post als spam beschouwd">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="gpBody">Boodschap</label>
                                            <div class="input-group">
                                                <span id="post-mess-span" class="input-group-addon">
                                                    <i class="fa fa-leanpub"></i>
                                                </span>
                                                <textarea class="form-control" id="gpBody" rows="3" name="gpBody"	placeholder="Schrijf hier je boodschap" required></textarea>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <button type="submit" class="btn btn-info col-xs-offset-10 col-xs-2" id="submitButton">Verzenden</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
            <div class="push"></div>
        </div>
        <footer>
            <?php
            include dirname(__FILE__) . '/includers/footer.php';
            ?>
        </footer>
        <?php
        include dirname(__FILE__) . '/includers/scripts.php';
        ?>
</html>