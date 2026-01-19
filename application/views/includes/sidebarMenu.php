<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                <img src="<?= base_url()?>/assets/img/admin-avatar.png" width="45px" />
            </div>
            <div class="admin-info">
                <div class="font-strong"><?=$this->session->userdata('SUPERBAT_NOM')?> </div><small>Administrateur</small></div>
            </div>
            <ul class="side-menu metismenu">
                <li>
                    <a class="active" href="<?= base_url()?>Acceuil"><i class="sidebar-item-icon fa fa-th-large"></i>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>
                <li class="heading">MODULES</li>

                <li>
                    <a href="javascript:;">
                        <i class="sidebar-item-icon fa fa-users"></i>
                        <span class="nav-label">Administration</span><i class="fa fa-angle-left arrow"></i></a>
                        <ul class="nav-2-level collapse">
                            <li>
                                <a href="<?= base_url()?>administration/Profil_Droit">Accès</a>
                            </li>
                            <li>
                             <a href="<?= base_url()?>administration/User">Utilisateurs</a>
                         </li>

                     </ul>
                 </li>
                     <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-bookmark"></i>
                        <span class="nav-label">Saisie & donnees</span><i class="fa fa-angle-left arrow"></i></a>
                        <ul class="nav-2-level collapse">
                            <li>
                                <a href="<?= base_url()?>stock_matieres/Fournisseur">Fournisseur</a>
                            </li>
                            <li>
                                <a href="<?= base_url()?>stock_matieres/Type_matieres">Type Matieres</a>
                            </li>
                            
                        </ul>
                    </li>
                 <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-bookmark"></i>
                        <span class="nav-label">Logistique</span><i class="fa fa-angle-left arrow"></i></a>
                        <ul class="nav-2-level collapse">
                            <li>
                                <a href="<?= base_url()?>stock_matieres/Stock_Matieres_New">Stock matieres</a>
                            </li>
                            
                        </ul>
                    </li>

                       <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-bookmark"></i>
                        <span class="nav-label">Matieres pemieres </span><i class="fa fa-angle-left arrow"></i></a>
                        <ul class="nav-2-level collapse">
                            <li>
                                <a href="<?= base_url()?>stock_matieres/Stock_Matieres_New/liste_reception"> Reception matières Locales</a>
                            </li>

                             <li>
                                <a href="<?= base_url()?>stock_matieres/Stock_Matieres_New/liste_reception2"> Reception matières internationales</a>
                            </li>
                            <li>
                                <a href="<?= base_url()?>stock_matieres/Commande_production">Commande production</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;"><i class="sidebar-item-icon fa fa-edit"></i>
                            <span class="nav-label">Production</span><i class="fa fa-angle-left arrow"></i></a>
                            <ul class="nav-2-level collapse">
                                <li>
                                    <a href="<?= base_url()?>production/Commander">Commander</a>
                                </li>
                                <li>
                                    <a href="<?= base_url()?>production/Consommation_Matieres_Premieres">Consommation MP</a>
                                </li>
                                <li>
                                    <a href="<?= base_url()?>production/Production/ajouter">Production</a>
                                </li>
                                <li>
                                    <a href="<?= base_url()?>production/Transferer">Transferer</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;"><i class="sidebar-item-icon fa fa-edit"></i>
                                <span class="nav-label">Stock produits finis</span><i class="fa fa-angle-left arrow"></i></a>
                                <ul class="nav-2-level collapse">
                                    <li>
                                        <a href="<?= base_url()?>produit_finis/Produits_Finis">Produits finis</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url()?>produit_finis/Produits_Finis/sortant">Sortie des produits</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:;"><i class="sidebar-item-icon fa fa-table"></i>
                                    <span class="nav-label">Vente</span><i class="fa fa-angle-left arrow"></i></a>
                                    <ul class="nav-2-level collapse">
                                        <li>
                                            <a href="<?= base_url()?>vente/Vente_Produits">Vente</a>
                                        </li>
                                        <li>
                                            <a href="#">Commande special</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>

