<!DOCTYPE html>
<html lang="en">
<?php include VIEWPATH.'includes/header.php'; ?>

<body class="fixed-navbar">
  <div class="page-wrapper">

    <!-- START HEADER -->
    <?php include VIEWPATH.'includes/navbar.php'; ?>
    <!-- END HEADER -->

    <!-- START SIDEBAR -->
    <?php include VIEWPATH.'includes/sidebarMenu.php'; ?>
    <!-- END SIDEBAR -->

    <div class="content-wrapper">

      <!-- PAGE HEADING -->
      <div class="page-heading">
        <h1 class="page-title"><?= $title; ?></h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.html"><i class="la la-home font-20"></i></a>
          </li>
          <li class="breadcrumb-item"><?= $title; ?></li>
        </ol>
      </div>

      <!-- PAGE CONTENT -->
      <div class="page-content fade-in-up">
        <div class="ibox">
          <div class="ibox-body">
            <form id="FormData" action="<?php echo base_url()?>first_materials/Stock_Entree/save" method="POST" enctype="multipart/form-data">
              
              <!-- Tabs Navigation -->
              <ul class="nav nav-tabs tabs-line">
                <li class="nav-item">
                  <a class="nav-link active bg-primary text-white" href="#tab-info1" data-toggle="tab">
                    <i class="fa fa-cube"></i> Enregistrement des matières premières
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#tab-info2" data-toggle="tab">
                    <i class="fa fa-user"></i> Détails du Profil
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#tab-info3" data-toggle="tab">
                    <i class="fa fa-cog"></i> Paramètres
                  </a>
                </li>
              </ul>

              <!-- Tabs Content -->
              <div class="tab-content">
                
                <!-- Tab 1: Enregistrement des matières premières -->
                <div class="tab-pane fade show active" id="tab-info1">
                  
                  <!-- Section Informations de Base -->
                  <div class="form-section mb-4">
                    <h5 class="section-title">
                      <i class="fas fa-info-circle me-2"></i>
                      Informations de Base
                    </h5>
                    <div class="row">
                      <div class="col-sm-6 form-group">
                        <label for="ID_TYPE_MATIERE" class="form-label required-field">Type de Matière</label>
                        <select class="form-control" id="ID_TYPE_MATIERE" name="ID_TYPE_MATIERE" required>
                          <option value="">Sélectionnez un type</option>
                           <?php
                    foreach ($type_mat as $typ) {
                     echo"<option value='".$typ['ID_TYPE_MATIERE']."'>".$typ['DESCRIPTION']."</option>";
                   }
                   ?>
                        </select>
                        <?php echo form_error('ID_TYPE_MATIERE', '<div class="text-danger">', '</div>'); ?>
                        <div class="form-text">Type de matière première</div>
                      </div>
                      
                      <div class="col-sm-6 form-group">
                        <label for="NUMERO_COLIS" class="form-label">Numéro de Colis</label>
                        <input type="text" class="form-control" id="NUMERO_COLIS" name="NUMERO_COLIS" 
                               placeholder="Ex: COL-2024-001" maxlength="50" value="<?= set_value('NUMERO_COLIS') ?>">
                        <?php echo form_error('NUMERO_COLIS', '<div class="text-danger">', '</div>'); ?>
                        <div class="form-text">Numéro d'identification du colis</div>
                      </div>
                    </div>
                  </div>

                  <!-- Section Caractéristiques Physiques -->
                  <div class="form-section mb-4">
                    <h5 class="section-title">
                      <i class="fas fa-ruler me-2"></i>
                      Caractéristiques Physiques
                    </h5>
                    <div class="row">
                      <div class="col-sm-6 form-group">
                        <label for="LONGEUR" class="form-label">Longueur (cm)</label>
                        <div class="input-group">
                          <input type="number" class="form-control" id="LONGEUR" name="LONGEUR" 
                                 placeholder="0" min="0" step="1" value="<?= set_value('LONGEUR') ?>">
                          <span class="input-group-text">cm</span>
                        </div>
                        <?php echo form_error('LONGEUR', '<div class="text-danger">', '</div>'); ?>
                        <div class="form-text">Longueur de la matière en centimètres</div>
                      </div>
                      
                      <div class="col-sm-6 form-group">
                        <label for="COULEUR" class="form-label">Couleur</label>
                        <input type="text" class="form-control" id="COULEUR" name="COULEUR" 
                               placeholder="Ex: Rouge, Bleu, Transparent" maxlength="100" value="<?= set_value('COULEUR') ?>">
                        <?php echo form_error('COULEUR', '<div class="text-danger">', '</div>'); ?>
                        <div class="form-text">Couleur de la matière première</div>
                      </div>
                    </div>
                  </div>

                  <!-- Section Quantités -->
                  <div class="form-section mb-4">
                    <h5 class="section-title">
                      <i class="fas fa-balance-scale me-2"></i>
                      Gestion des Quantités
                    </h5>
                    <div class="row">
                      <div class="col-sm-6 form-group">
                        <label for="QUANTITE_COMMANDE" class="form-label required-field">Quantité Commandée</label>
                        <div class="input-group">
                          <input type="number" class="form-control" id="QUANTITE_COMMANDE" name="QUANTITE_COMMANDE" 
                                 placeholder="0" min="0" step="1" required value="<?= set_value('QUANTITE_COMMANDE') ?>">
                          <span class="input-group-text">unités</span>
                        </div>
                        <?php echo form_error('QUANTITE_COMMANDE', '<div class="text-danger">', '</div>'); ?>
                        <div class="form-text">Quantité initialement commandée</div>
                      </div>
                      
                      <div class="col-sm-6 form-group">
                        <label for="QUANTITE_RECUE" class="form-label required-field">Quantité Reçue</label>
                        <div class="input-group">
                          <input type="number" class="form-control" id="QUANTITE_RECUE" name="QUANTITE_RECUE" 
                                 placeholder="0" min="0" step="1" required value="<?= set_value('QUANTITE_RECUE') ?>">
                          <span class="input-group-text">unités</span>
                        </div>
                        <?php echo form_error('QUANTITE_RECUE', '<div class="text-danger">', '</div>'); ?>
                        <div class="form-text">Quantité effectivement reçue</div>
                      </div>
                    </div>
                    
                    <!-- Affichage de la différence -->
                    <div class="row">
                      <div class="col-12">
                        <div class="alert alert-info" id="quantiteDifference">
                          <i class="fas fa-calculator me-2"></i>
                          Différence : <span id="differenceValue">0</span> unités
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Section Fournisseur et Utilisateur -->
                  <div class="form-section mb-4">
                    <h5 class="section-title">
                      <i class="fas fa-users me-2"></i>
                      Responsables
                    </h5>
                    <div class="row">
                      <div class="col-sm-6 form-group">
                        <label for="ID_FOURNISSEUR" class="form-label required-field">Fournisseur</label>
                        <select class="form-control" id="ID_FOURNISSEUR" name="ID_FOURNISSEUR" required>
                          <option value="">Sélectionnez un fournisseur</option>
                          <?php
                    foreach ($fournisseurs as $fourn) {

                      $val='';


                     echo"<option value='".$fourn['ID_FOURNISSEUR']."'>".$fourn['NOM_FOURNISSEUR']."</option>";
                   }
                   ?>
                        </select>
                        <?php echo form_error('ID_FOURNISSEUR', '<div class="text-danger">', '</div>'); ?>
                        <div class="form-text">Fournisseur de la matière première</div>
                      </div>
                      
                      <div class="col-sm-6 form-group">
                        <label for="ID_USER" class="form-label required-field">Utilisateur Responsable</label>
                        <select class="form-control" id="ID_USER" name="ID_USER" required>
                           <?php
                    foreach ($users as $user) {
                     echo"<option value='".$user['ID_USER']."'>".$user['NOM']." ".$user['PRENOM']."</option>";
                   }
                   ?>
                        </select>
                        <?php echo form_error('ID_USER', '<div class="text-danger">', '</div>'); ?>
                        <div class="form-text">Utilisateur ayant effectué l'opération</div>
                      </div>
                    </div>
                  </div>

                  <!-- Section Date -->
                  <div class="form-section mb-4">
                    <h5 class="section-title">
                      <i class="fas fa-calendar-alt me-2"></i>
                      Date d'Entrée
                    </h5>
                    <div class="row">
                      <div class="col-sm-6 form-group">
                        <label for="DATE_ENTREE" class="form-label required-field">Date d'Entrée en Stock</label>
                        <input type="datetime-local" class="form-control" id="DATE_ENTREE" name="DATE_ENTREE" required value="<?= set_value('DATE_ENTREE') ?>">
                        <?php echo form_error('DATE_ENTREE', '<div class="text-danger">', '</div>'); ?>
                        <div class="form-text">Date et heure de réception en stock</div>
                      </div>
                    </div>
                  </div>

                </div>

                <!-- Tab 2: Détails du Profil -->
                <div class="tab-pane fade" id="tab-info2">
                  <div class="row pt-4">
                    <div class="col-sm-6 form-group">
                      <label>E-mail <span class="text-danger">*</span></label>
                      <input class="form-control" type="email" id="USERNAME" name="USERNAME" placeholder="username@superbat.bi" value="<?= set_value('USERNAME') ?>">
                      <?php echo form_error('USERNAME', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label>Password <span class="text-danger">*</span></label>
                      <div class="input-group-icon right">
                        <div class="input-icon">
                          <i class="fa fa-eye" id="togglePassword" style="cursor:pointer;"></i>
                        </div>
                        <input class="form-control" type="password" id="PASSWORD" name="PASSWORD" value="<?= set_value('PASSWORD') ?>">
                      </div>
                      <?php echo form_error('PASSWORD', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    
                    <!-- Deux champs en bas du Tab 2 -->
                    <div class="col-sm-6 form-group">
                      <label>Département</label>
                      <select class="form-control" id="DEPARTEMENT" name="DEPARTEMENT">
                        <option value="">Sélectionnez un département</option>
                        <option value="IT" <?= set_select('DEPARTEMENT', 'IT') ?>>IT</option>
                        <option value="RH" <?= set_select('DEPARTEMENT', 'RH') ?>>Ressources Humaines</option>
                        <option value="Finance" <?= set_select('DEPARTEMENT', 'Finance') ?>>Finance</option>
                        <option value="Marketing" <?= set_select('DEPARTEMENT', 'Marketing') ?>>Marketing</option>
                      </select>
                      <?php echo form_error('DEPARTEMENT', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label>Poste</label>
                      <input class="form-control" type="text" id="POSTE" name="POSTE" placeholder="Poste occupé" value="<?= set_value('POSTE') ?>">
                      <?php echo form_error('POSTE', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>
                </div>

                <!-- Tab 3: Paramètres -->
                <div class="tab-pane fade" id="tab-info3">
                  <div class="row pt-4">
                    <div class="col-sm-6 form-group">
                      <label>Rôle Utilisateur</label>
                      <select class="form-control" id="ROLE" name="ROLE">
                        <option value="">Sélectionnez un rôle</option>
                        <option value="admin" <?= set_select('ROLE', 'admin') ?>>Administrateur</option>
                        <option value="user" <?= set_select('ROLE', 'user') ?>>Utilisateur</option>
                        <option value="manager" <?= set_select('ROLE', 'manager') ?>>Manager</option>
                        <option value="viewer" <?= set_select('ROLE', 'viewer') ?>>Observateur</option>
                      </select>
                      <?php echo form_error('ROLE', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label>Statut</label>
                      <select class="form-control" id="STATUT" name="STATUT">
                        <option value="actif" <?= set_select('STATUT', 'actif') ?>>Actif</option>
                        <option value="inactif" <?= set_select('STATUT', 'inactif') ?>>Inactif</option>
                        <option value="suspendu" <?= set_select('STATUT', 'suspendu') ?>>Suspendu</option>
                      </select>
                      <?php echo form_error('STATUT', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    
                    <!-- Deux champs en bas du Tab 3 -->
                    <div class="col-sm-6 form-group">
                      <label>Date d'embauche</label>
                      <input class="form-control" type="date" id="DATE_EMBAUCHE" name="DATE_EMBAUCHE" value="<?= set_value('DATE_EMBAUCHE') ?>">
                      <?php echo form_error('DATE_EMBAUCHE', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label>Photo de profil</label>
                      <input class="form-control" type="file" id="PHOTO" name="PHOTO" accept="image/*">
                      <?php echo form_error('PHOTO', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>
                </div>

              </div>

              <!-- Submit Button -->
              <div class="form-group mt-4">
                <button class="btn btn-success btn-block" type="submit">Soumettre</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- END PAGE CONTENT -->

    </div>
  </div>

  <!-- SETTINGS / BACKDROPS -->
  <?php include VIEWPATH.'includes/settings.php'; ?>
  <div class="sidenav-backdrop backdrop"></div>
  <div class="preloader-backdrop">
    <div class="page-preloader">Loading</div>
  </div>

  <!-- SCRIPTS -->
  <?php include VIEWPATH.'includes/scripts.php'; ?>

  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const input = document.getElementById('PASSWORD');
      const icon = this;
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });

    // Script pour gérer la couleur de fond des onglets actifs
    document.addEventListener('DOMContentLoaded', function() {
      // Gestion des tabs principaux
      const mainTabs = document.querySelectorAll('.nav-tabs.tabs-line .nav-link');
      mainTabs.forEach(tab => {
        tab.addEventListener('click', function() {
          // Retirer les classes actives de tous les tabs principaux
          mainTabs.forEach(t => {
            t.classList.remove('active', 'bg-primary', 'text-white');
          });
          // Ajouter les classes actives au tab cliqué
          this.classList.add('active', 'bg-primary', 'text-white');
        });
      });

      // Calcul automatique de la différence entre quantité commandée et reçue
      const quantiteCommandee = document.getElementById('QUANTITE_COMMANDE');
      const quantiteRecue = document.getElementById('QUANTITE_RECUE');
      const differenceValue = document.getElementById('differenceValue');
      
      function calculerDifference() {
        const commandee = parseInt(quantiteCommandee.value) || 0;
        const recue = parseInt(quantiteRecue.value) || 0;
        const difference = recue - commandee;
        
        differenceValue.textContent = difference;
        
        // Changer la couleur selon la différence
        const alertDiv = document.getElementById('quantiteDifference');
        if (difference > 0) {
          alertDiv.className = 'alert alert-warning';
          differenceValue.innerHTML = `<strong>+${difference}</strong> (Surplus)`;
        } else if (difference < 0) {
          alertDiv.className = 'alert alert-danger';
          differenceValue.innerHTML = `<strong>${difference}</strong> (Manquant)`;
        } else {
          alertDiv.className = 'alert alert-success';
          differenceValue.innerHTML = `<strong>${difference}</strong> (Exact)`;
        }
      }
      
      if (quantiteCommandee && quantiteRecue) {
        quantiteCommandee.addEventListener('input', calculerDifference);
        quantiteRecue.addEventListener('input', calculerDifference);
      }

      // Initialiser avec la date actuelle seulement si le champ est vide
      const now = new Date();
      now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
      const dateEntree = document.getElementById('DATE_ENTREE');
      if (dateEntree && !dateEntree.value) {
        dateEntree.value = now.toISOString().slice(0, 16);
      }
    });
  </script>

  <style>
    .nav-tabs .nav-link.active.bg-primary {
      background-color: #007bff !important;
      border-color: #007bff !important;
      color: white !important;
    }
    
    .nav-tabs .nav-link {
      transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover {
      background-color: #e9ecef;
    }
    
    .nav-tabs .nav-link.active.bg-primary:hover {
      background-color: #0056b3 !important;
      border-color: #0056b3 !important;
    }

    .required-field::after {
      content: " *";
      color: red;
    }
    
    .form-section {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 15px;
      border-left: 4px solid #007bff;
    }
    
    .section-title {
      color: #2c3e50;
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 15px;
    }
    
    .form-text {
      font-size: 12px;
      color: #6c757d;
      margin-top: 5px;
    }
    
    .text-danger {
      font-size: 12px;
      margin-top: 5px;
    }
  </style>
</body>
</html>