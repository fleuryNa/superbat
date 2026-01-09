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



                  <div class="ibox-body">

            <table class="table table-striped table-bordered table-hover table-modern" id="mytable" cellspacing="0" width="100%">
              <thead>
                 <tr>
           
                    <th>Utilisateur</th>
                    <th>Matiere</th>
                    <th>Numero colis</th>
                    <th>Quantite commande</th>

                    <th>Quantite Recue</th>
                    <th>Fournisseur</th>
                    <th>Date d'entree</th>
                    <th>Actions</th> 
                  </tr>
              </thead>
              <tbody>
                <!-- Les données seront chargées dynamiquement par DataTables -->
              </tbody>
            </table>
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
  $(document).ready(function(){
    liste_search();
  });

  function liste_search() {
    var url = "<?= base_url() ?>first_materials/Stock_Entree/list";
    var row_count = "1000000";
    table = $("#mytable").DataTable({
      "processing": true,
      "destroy": true,
      "serverSide": true,
      "order": [[0, 'desc']],
      "ajax": { url: url, type: "POST" },
      lengthMenu: [[5, 10, 50, 100, row_count], [5, 10, 50, 100, "All"]],
      pageLength: 10,
      "columnDefs": [{ "targets": [], "orderable": false }],
      dom: 'Bfrtlip',
      buttons: ['copy', 'excel', 'pdf'],
      language: {
        "sProcessing": "Traitement en cours...",
        "sSearch": "Rechercher&nbsp;:",
        "sLengthMenu": "Afficher _MENU_ éléments",
        "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
        "sInfoEmpty": "Aucun élément",
        "sZeroRecords": "Aucun résultat",
        "sEmptyTable": "Aucune donnée disponible",
        "oPaginate": {
          "sFirst": "Premier",
          "sPrevious": "Précédent",
          "sNext": "Suivant",
          "sLast": "Dernier"
        }
      }
    });
  }
</script>

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