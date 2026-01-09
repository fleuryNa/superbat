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
          include 'includes/menu_vente_produits.php';
          ?>

          <div class="ibox-body">
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link active" href="#tab-1-1" data-toggle="tab"><i class="fa fa-line-chart"></i> Toles </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#tab-1-2" data-toggle="tab"><i class="fa fa-heartbeat"></i> Clous</a>
              </li>

            </ul>
            <div class="tab-content">
              <div class="tab-pane fade show active" id="tab-1-1"> 
                <div class="table-responsive">
                 <table class="table table-striped table-bordered table-hover table-modern" id="mytable" cellspacing="0" width="100%">
                  <thead>
                    <tr> 
                      <th>N Facture</th>
                      <th>Utilisateur</th>
                      <th>Client</th>
                      <th>Produit</th>
                      <th>Type</th>
                      <th>Quantite</th>
                      <th>P.U</th>
                      <th>Statut</th>
                      <th>Date</th>
                      <th>Actions</th>

                    </tr>
                  </thead>
                  <tbody>
                    <!-- Les données seront chargées dynamiquement par DataTables -->
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-1-2">  
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-modern" id="mytable3" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>N Facture</th>
                      <th>Utilisateur</th>
                      <th>Client</th>
                      <th>Produit</th>
                      <th>Type</th>
                      <th>Quantite</th>
                      <th>P.U</th>
                      <th>Statut</th>
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
    </div>
    <!-- END PAGE CONTENT -->

    <?php include VIEWPATH.'includes/footer.php'; ?>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalColonnestoles" tabindex="-1" role="dialog" aria-labelledby="modalColonnesLabel" aria-hidden="true">
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
                <th>N Facture</th>
                <th>Utilisateur</th>
                <th>Client</th>
                <th>Produit</th>
                <th>Type</th>
                <th>Quantite</th>
                <th>P.U</th>
                <th>Statut</th>
                <th>Date</th>
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
<div class="modal fade" id="modalColonnesclous" tabindex="-1" role="dialog" aria-labelledby="modalColonnesLabel" aria-hidden="true">
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
          <table class="table table-bordered table-striped" id="mytable_histo_clous">
            <thead class="thead-light">
              <tr>
                <th>N Facture</th>
                <th>Utilisateur</th>
                <th>Client</th>
                <th>Produit</th>
                <th>Type</th>
                <th>Quantite</th>
                <th>P.U</th>
                <th>Statut</th>
                <th>Date</th>
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
    liste_commande();
    liste_livre();

  });

  function liste_commande() {
    var url = "<?= base_url() ?>vente/Vente_Produits/listing";
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
    var url = "<?= base_url() ?>vente/Vente_Produits/listing_clous";
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


<script>
  function get_histo_toles(NUMERO_FATURE) {
      alert(NUMERO_FATURE)
    // Ouvrir le modal Bootstrap
    $('#modalColonnestoles').modal('show');

    // Attendre que le modal soit visible
    $('#modalColonnestoles').on('shown.bs.modal', function () {

      var url = "<?= base_url() ?>vente/Vente_Produits/get_details";
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
          data: { NUMERO_FATURE: NUMERO_FATURE }
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
  function get_histo_clous(NUMERO_FATURE) {
      alert(NUMERO_FATURE)
    // Ouvrir le modal Bootstrap
    $('#modalColonnesclous').modal('show');

    // Attendre que le modal soit visible
    $('#modalColonnesclous').on('shown.bs.modal', function () {

      var url = "<?= base_url() ?>vente/Vente_Produits/get_details_clous";
      var row_count = 1000000;

      if ($.fn.DataTable.isDataTable('#mytable_histo_clous')) {
        $('#mytable_histo_clous').DataTable().clear().destroy();
      }


      $('#mytable_histo_clous').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: {
          url: url,
          type: "POST",
          data: { NUMERO_FATURE: NUMERO_FATURE }
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
