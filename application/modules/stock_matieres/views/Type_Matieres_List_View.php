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
          include 'includes/menu_type_matieres.php';
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

            <table class="table table-striped table-bordered table-hover table-modern" id="mytable" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>Description</th>
                  <th>Unite de mesure</th>
                  <th>Abbreviation</th>
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
      <!-- END PAGE CONTENT -->

      <?php include VIEWPATH.'includes/footer.php'; ?>
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
    var url = "<?= base_url() ?>stock_matieres/Type_matieres/listing";
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
  $(document).ready(function () {
    setTimeout(function () {
      $('.alert').fadeOut('slow');
    }, 4000);
  });

</script>
</body>
</html>
