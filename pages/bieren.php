<!DOCTYPE html>
<html lang="nl">
    <head>
        <?php
        include dirname(__FILE__) . '/includers/header.php';
        ?>
        <title>Bieren</title>
    </head>
    <body class="green-grid">
        <div class="wrap">
            <header>
                <?php
                $page = 'beers';
                include dirname(__FILE__) . '/includers/menu.php';
                ?>
            </header>
            <main role="main" class="first container-fluid">
                <div class="row">
                    <div id="col1" class="col-lg-offset-1 col-lg-5">
                        <div id="op-fles" class="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h1 class="panel-title">bieren op fles (<?php echo count($this->getBeerController()->getBeersBottle(FALSE)); ?>)</h1>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Naam</th>
                                                <th>Graden</th>
                                                <th>Brouwerij</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $beersBottle = $this->getBeerController()->getBeersBottle(FALSE);
                                            foreach ($beersBottle as $key => $beerBottle) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $beerBottle->getName() ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $beerBottle->getDegrees(); ?> &deg;
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $beerBottle->getBrewery_url(); ?>">
                                                            <?php echo $beerBottle->getBrewery_name(); ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="col2" class="col-lg-5">
                        <div id="vat" class="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h1 class="panel-title">van&prime;t vat (<?php echo count($this->getBeerController()->getBeersTap(FALSE)) ?>)</h1>
                                </div>
                                <div class="panel-body">
                                    <table id="vat_tab" class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Naam</th>
                                                <th>Graden</th>
                                                <th>Brouwerij</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $beersTap = $this->getBeerController()->getBeersTap(FALSE);
                                            foreach ($beersTap as $key => $beerTap) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $beerTap->getName() ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $beerTap->getDegrees(); ?> &deg;
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $beerTap->getBrewery_url(); ?>">
                                                            <?php echo $beerTap->getBrewery_name(); ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="trappisten" class="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h1 class="panel-title">trappisten (<?php echo count($this->getBeerController()->getBeersTrappist(FALSE)) ?>)</h1>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Naam</th>
                                                <th>Graden</th>
                                                <th>Brouwerij</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $beersTrappist = $this->getBeerController()->getBeersTrappist(FALSE);
                                            foreach ($beersTrappist as $key => $beerTrap) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $beerTrap->getName() ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $beerTrap->getDegrees(); ?> &deg;
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $beerTrap->getBrewery_url(); ?>">
                                                            <?php echo $beerTrap->getBrewery_name(); ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
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

    </body>
</html>