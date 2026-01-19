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


      <!-- PAGE HEADING (si tu veux le garder aussi) -->
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

          <?php 
          include 'includes/menu_stock_new.php';
          ?>
          <div class="ibox-body">

            <div id="alertBox">
              <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show text-center">
                  <i class="fa fa-check-circle"></i>
                  <?= $this->session->flashdata('success'); ?>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
              <?php endif; ?>

              <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show text-center">
                  <i class="fa fa-exclamation-triangle"></i>
                  <?= $this->session->flashdata('error'); ?>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
              <?php endif; ?>
            </div>
            <div class="table-responsive">
             <table class="table table-responsive table-striped table-bordered table-hover table-modern" id="mytable" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>Type matiere</th>
                  <th>Qté reçue</th>
                  <th>Fournisseur</th>
                  <th>Utilisateur</th>
                  <th>Date d'entrée</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <!-- Les données seront chargées dynamiquement par DataTables -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- END PAGE CONTENT -->

    <?php include VIEWPATH.'includes/footer.php'; ?>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalColonnes" tabindex="-1" role="dialog" aria-labelledby="modalColonnesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 70%;">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalColonnesLabel">Détails des informations</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="mytable_histo">
            <thead class="thead-light">
              <tr>
                <th>Date d'entrée</th>
                <th>Utilisateur</th>
                <th>Fournisseur</th>
                <th>Type matière</th>
                
                <th>Qté</th>
                <th>Motif</th>
                <th>Statut</th>
                <th>Options</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="9" class="text-center text-muted">
                  Aperçu des colonnes (pas de données)
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>

    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalpacking" tabindex="-1" role="dialog" aria-labelledby="modalpackingLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 70%;">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalpackingLabel">Détails des informations</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="mytable_pack">
            <thead class="thead-light">
              <tr>
                <th>Date d'entrée</th>
                <th>Type matière</th>
                <th>Size</th>
                <th>Coils number</th>
                <th>Couleur</th>
                <th>Longueur</th>
                <th>Poids net</th>
                <th>Poids brut</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="9" class="text-center text-muted">
                  Aperçu des colonnes (pas de données)
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>

    </div>
  </div>
</div>


<!-- SETTINGS / BACKDROPS -->
<!-- <?php include VIEWPATH.'includes/settings.php'; ?> -->
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
    var url = "<?= base_url() ?>stock_matieres/Stock_Matieres_New/listing";
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
  function get_histo(id) {
      // alert(id)
    // Ouvrir le modal Bootstrap
    $('#modalColonnes').modal('show');

    // Attendre que le modal soit visible
    $('#modalColonnes').on('shown.bs.modal', function () {

      var url = "<?= base_url() ?>stock_matieres/Stock_Matieres_New/get_details";
      var row_count = 1000000;

      if ($.fn.DataTable.isDataTable('#mytable_histo')) {
        $('#mytable_histo').DataTable().clear().destroy();
      }


      $('#mytable_histo').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: {
          url: url,
          type: "POST",
          data: { id: id }
        },
        lengthMenu: [[5, 10, 50, 100, row_count], [5, 10, 50, 100, "All"]],
        pageLength: 10,
        dom: 'Bfrtlip',
        buttons: ['copy', 'excel', 'pdf'],
        language: {
          sProcessing: "Traitement en cours...",
          sSearch: "Rechercher :",
          sLengthMenu: "Afficher _MENU_ éléments",
          sInfo: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
          sInfoEmpty: "Aucun élément",
          sZeroRecords: "Aucun résultat",
          sEmptyTable: "Aucune donnée disponible",
          oPaginate: {
            sFirst: "Premier",
            sPrevious: "Précédent",
            sNext: "Suivant",
            sLast: "Dernier"
          }
        }
      });

    });
  }
</script>

<script>
  function get_packing(id) {
      // alert(id)
    // Ouvrir le modal Bootstrap
    $('#modalpacking').modal('show');

    // Attendre que le modal soit visible
    $('#modalpacking').on('shown.bs.modal', function () {

      var url = "<?= base_url() ?>stock_matieres/Stock_Matieres_New/get_packing";
      var row_count = 1000000;

      if ($.fn.DataTable.isDataTable('#mytable_histo')) {
        $('#mytable_pack').DataTable().clear().destroy();
      }


      $('#mytable_pack').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: {
          url: url,
          type: "POST",
          data: { id: id }
        },
        lengthMenu: [[5, 10, 50, 100, row_count], [5, 10, 50, 100, "All"]],
        pageLength: 10,
        dom: 'Bfrtlip',
        buttons: ['copy', 'excel', 'pdf'],
        language: {
          sProcessing: "Traitement en cours...",
          sSearch: "Rechercher :",
          sLengthMenu: "Afficher _MENU_ éléments",
          sInfo: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
          sInfoEmpty: "Aucun élément",
          sZeroRecords: "Aucun résultat",
          sEmptyTable: "Aucune donnée disponible",
          oPaginate: {
            sFirst: "Premier",
            sPrevious: "Précédent",
            sNext: "Suivant",
            sLast: "Dernier"
          }
        }
      });

    });
  }
</script>

</body>
</html>
