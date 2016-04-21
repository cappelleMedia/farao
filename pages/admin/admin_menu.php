    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php?action=adminPortal">Farao admin portaal</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-alerts">
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-comment fa-fw"></i> Nieuwe boodschap
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                            <span class="pull-right text-muted small">12 minutes ago</span>
                        </div>
                    </a>
                </li>

                <li class="divider"></li>
                <li>
                    <a class="text-center" href="#">
                        <strong>Bekijk alle notificaties</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
            <!-- /.dropdown-alerts -->
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <?php echo $_SESSION['current_user']->getUser_name(); ?>
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="index.php?action=logout"><i class="fa fa-sign-out fa-fw"></i> Uit loggen</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="index.php?action=adminPortal" data-ajax-type="" class="<?php echo ($page === 'portal' ? 'active' : '') ?> ajax-trigger"><i class="fa fa-dashboard fa-fw"></i> Portaal</a>
                </li>

                <li>
                    <a href="index.php?action=adminBeerMgr" data-ajax-type="" class="<?php echo ($page === 'beermgr' ? 'active' : '') ?> ajax-trigger"><i class="fa fa-beer fa-fw"></i> Bier Manager</a>
                </li>
                <li>
                    <a href="index.php?action=adminPromoMgr" data-ajax-type="" class="<?php echo ($page === 'promomgr' ? 'active' : '') ?> ajax-trigger" ><i class="fa fa-bullhorn fa-fw"></i> Promotie Manager</a>
                </li>
                <li>
                    <a href="index.php?action=adminEventMgr" data-ajax-type="" class="<?php echo ($page === 'eventmgr' ? 'active' : '') ?> ajax-trigger"><i class="fa fa-calendar fa-fw"></i> Evenement Manager</a>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->