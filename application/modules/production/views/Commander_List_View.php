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
          include 'includes/menu_commande.php';
          ?>

          <div class="ibox-body">
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link active" href="#tab-1-1" data-toggle="tab"><i class="fa fa-line-chart"></i> Commander</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#tab-1-2" data-toggle="tab"><i class="fa fa-heartbeat"></i> Livrer</a>
              </li>

            </ul>
            <div class="tab-content">
              <div class="tab-pane fade show active" id="tab-1-1"> 
               <table class="table table-striped table-bordered table-hover table-modern" id="mytable" cellspacing="0" width="100%">
                <thead>
                  <tr> 
                   <th>Gestionnaire</th>
                   <th>Type matières</th>
                   <th>Nombre colis</th>
                   <th>Quantité (Tonne)</th>
                   <th>Date</th>
                   <th>Actions</th>
                 </tr>
               </thead>
               <tbody>
                <!-- Les données seront chargées dynamiquement par DataTables -->
              </tbody>
            </table></div>
            <div class="tab-pane" id="tab-1-2">  
              <table class="table table-striped table-bordered table-hover table-modern" id="mytable3" cellspacing="0" width="100%">
                <thead>
                  <tr>
                   <th>Gestionnaire</th>
                   <th>Type matières</th>
                   <th>Nombre colis</th>
                   <th>Quantité (Tonne)</th>
                   <th>Date</th>
                   <th>Actions</th>
                 </tr>
               </thead>
               <tbody>
                
               </tbody>
             </table>
           </div>
         </div>

       </div>
     </div>
   </div>
   <!-- END PAGE CONTENT -->

   <?php include VIEWPATH.'includes/footer.php'; ?>
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
    liste_commande();
    liste_livre();

  });

  function liste_commande() {
    var url = "<?= base_url() ?>production/Commander/listing";
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

  function liste_livre() {
    var url = "<?= base_url() ?>production/Commander/listing_livrer";
    var row_count = "1000000";
    table = $("#mytable3").DataTable({
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


</body>
</html>
