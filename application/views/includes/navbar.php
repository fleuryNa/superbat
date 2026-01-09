  <style>

      .brand-img {
        height: 50px;
    }

    .brand-img-mini {
        height: 30px;
        display: none; /* s'affiche seulement en mode mini si tu veux */
    }

    /* Nom utilisateur : autoriser retour à la ligne */
    .user-name {
        max-width: 160px;   /* ajuste si besoin */
        display: inline-block;
        white-space: normal;  /* autorise le retour à la ligne */
        word-break: break-word; /* coupe les mots trop longs */
        vertical-align: middle;
    }

/* Dropdown : largeur automatique */
.user-dropdown {
    min-width: 220px;
    max-width: 300px;
}

/* Items du menu */
.user-dropdown .dropdown-item {
    white-space: normal;   /* autorise texte sur plusieurs lignes */
    word-break: break-word;
}


</style>


<header class="header">
    <div class="page-brand">
        <a class="link" href="<?= base_url()?>Acceuil" style="width:100%; display:block;">
            <img src="<?= base_url()?>assets/img/logos/supertbat.png" alt="Logo" class="brand-img" style="width:100%; height:auto;">
        </a>
    </div>

    <div class="flexbox flex-1">
        <!-- START TOP-LEFT TOOLBAR-->
        <ul class="nav navbar-toolbar">
            <li>
                <a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="ti-menu"></i></a>
            </li>
            <li>
              <!--   <form class="navbar-search" action="javascript:;">
                    <div class="rel">
                        <span class="search-icon"><i class="ti-search"></i></span>
                        <input class="form-control" placeholder="Search here...">
                    </div>
                </form> -->
            </li>
        </ul>
        <!-- END TOP-LEFT TOOLBAR-->
        <!-- START TOP-RIGHT TOOLBAR-->
        <ul class="nav navbar-toolbar">

           <li class="dropdown dropdown-user">
            <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                <img src="<?= base_url()?>/assets/img/admin-avatar.png" />
                <span class="user-name">
                    <?= $this->session->userdata('SUPERBAT_NOM') ?>
                </span>
                <i class="fa fa-angle-down m-l-5"></i>
            </a>

            <ul class="dropdown-menu dropdown-menu-right user-dropdown">
                <li>
                    <a class="dropdown-item" href="<?= base_url()?>/Login/forgotPassword">
                        <i class="fa fa-support"></i> Changer mot de passe
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="<?= base_url()?>/Login/do_logout">
                        <i class="fa fa-power-off"></i> Déconnexion
                    </a>
                </li>
            </ul>
        </li>

    </ul>
    <!-- END TOP-RIGHT TOOLBAR-->
</div>
</header>