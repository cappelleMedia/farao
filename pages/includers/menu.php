<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid"> 
        <div class="row">
            <div class="navbar-header navbar-inner">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#prim-nav"> 
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span> 
                    <span class="icon-bar"></span> 
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span> 
                </button>
                <figure>
                    <a class="navbar-brand img-rounded" href="index.php?action=home" tabindex="1" title="link naar index pagina">
                        <img class="img-rounded " src="images/logo/faraotmp.png" alt="Logo Café Farao">
                    </a> 
                </figure>
            </div>
            <div class="collapse navbar-collapse col-lg-10 col-md-10 col-sm-10 col-sm-offset-1 col-lg-offset-1 col-md-offset-1 col-xs-offset-0" id="prim-nav">
                <ul class="nav nav-pills nav-justified">
                    <li class="<?php echo ($page === 'home' ? 'active' : '') ?>"><a href="index.php?action=home">Home</a></li>
                    <li class="<?php echo ($page === 'beers' ? 'active' : '') ?>"><a href="index.php?action=beers">Bieren</a></li>
                    <li class="<?php echo ($page === 'pics' ? 'active' : '') ?>"><a href="index.php?action=pics">Foto's</a></li>
                    <li class="<?php echo ($page === 'promos' ? 'active' : '') ?>"><a href="index.php?action=promos">Promo's</a></li>
                    <li class="<?php echo ($page === 'guestbook' ? 'active' : '') ?>"><a href="index.php?action=guestbook">Gastenboek</a></li>
                    <li class="<?php echo ($page === 'contact' ? 'active' : '') ?>"><a href="index.php?action=contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>