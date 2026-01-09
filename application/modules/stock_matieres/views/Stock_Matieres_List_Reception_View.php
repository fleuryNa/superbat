<!DOCTYPE html>
<html lang="fr">
<?php include VIEWPATH.'includes/header.php'; ?>

<body class="fixed-navbar">
    <div class="page-wrapper">

        <!-- HEADER -->
        <?php include VIEWPATH.'includes/navbar.php'; ?>

        <!-- SIDEBAR -->
        <?php include VIEWPATH.'includes/sidebarMenu.php'; ?>

        <div class="content-wrapper">

            <!-- PAGE HEADING -->
            <div class="page-heading">
                <h1 class="page-title"><?= $title; ?></h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item"><?= $title; ?></li>
                </ol>
            </div>

            <!-- PAGE CONTENT -->
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-body">
                     <ul class="nav nav-tabs">
                      <li class="nav-item">
                        <a class="nav-link active" href="#tab-1-1" data-toggle="tab"><i class="fa fa-line-chart"></i> Réception</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab-1-2" data-toggle="tab"><i class="fa fa-heartbeat"></i> Stock</a>
                    </li>

                </ul>

                <div class="tab-content">
                  <div class="tab-pane fade show active" id="tab-1-1"> 
                    <form action="<?= base_url('stock_matieres/Stock_matieres/traitement'); ?>" method="POST">

                        <table class="table table-striped table-bordered table-hover table-modern" id="mytable" width="100%">
                            <thead>
                                <tr>
                                    <th>Date d'entrée</th>
                                    <th>Utilisateur</th>
                                    <th>Type matiere</th>
                                    <th>Qté commandée</th>
                                    <th>Qté reçue</th>

                                    <th>#</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($commandes as $cmd): ?>
                                <tr>
                                    <td><?= date("d/m/Y", strtotime($cmd['DATE_ENTREE'])); ?></td>
                                    <td><?= $cmd['user']; ?></td>
                                    <td><?= $cmd['DESCRIPTION']; ?></td>

                                    <td><?= $cmd['QUANTITE_COMMANDE']; ?>
                                        <input type="hidden" name="id_matiere[]" value="<?= $cmd['ID_TYPE_MATIERE']; ?>">

                                    </td>


                                    <td class="text-center">
                                        <input 
                                        type="text" 
                                        name="quantite_recu[]" 
                                        style="width:80%;"
                                        class="form-control"
                                        value="<?= $cmd['QUANTITE_RECUE']; ?>"
                                        >

                                    </td>

                                    <!-- ID caché pour récupération -->
                                    <input type="hidden" name="id_stock[]" value="<?= $cmd['ID_STOCK_MATIERE']; ?>">

                                    <!-- Checkbox -->
                                    <td class="text-center">
                                        <label class="ui-checkbox ui-checkbox-primary">
                                            <input type="checkbox" name="select_stock[]" value="<?= $cmd['ID_STOCK_MATIERE']; ?>">
                                            <span class="input-span"></span>
                                        </label>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="text-right mt-6">
                            <button type="submit" class="btn btn-primary">
                                Enregistrer la réception des matières premières
                            </button>
                        </div>

                    </form>
                </div>
                <div class="tab-pane" id="tab-1-2">
                   <div class="table-responsive">  
                     <table class="table table-striped table-bordered table-hover table-modern" id="mytable3" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                              <th>Type matiere</th>
                              <th>Colis</th>
                              <th>Longueur</th>
                              <th>Qté commandée</th>
                              <th>Qté restante</th>
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
</div>

<?php include VIEWPATH.'includes/footer.php'; ?>
</div>
</div>

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
            <th>Type matière</th>
            <th>Qté commandée</th>
            <th>Qté reçue</th>
            <th>Fournisseur</th>
            <th>Utilisateur</th>
            <th>Motif</th>
            <th>Statut</th>
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



<!-- <?php include VIEWPATH.'includes/settings.php'; ?> -->

<div class="sidenav-backdrop backdrop"></div>
<div class="preloader-backdrop">
    <div class="page-preloader">Loading</div>
</div>

<?php include VIEWPATH.'includes/scripts.php'; ?>

<script>
    $(document).ready(function () {
        initDataTable();
    });

    function initDataTable() {
        $("#mytable").DataTable({
            lengthMenu: [[5, 10, 50, 100, -1], [5, 10, 50, 100, "Tous"]],
            pageLength: 10,
            dom: 'Bfrtlip',
            buttons: ['copy', 'excel', 'pdf', 'print'],
            language: {
                sProcessing:     "Traitement en cours...",
                sSearch:         "Rechercher :",
                sLengthMenu:     "Afficher _MENU_ éléments",
                sInfo:           "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                sInfoEmpty:      "Aucun élément",
                sZeroRecords:    "Aucun résultat trouvé",
                sEmptyTable:     "Aucune donnée disponible",
                oPaginate: {
                    sFirst:      "Premier",
                    sPrevious:   "Précédent",
                    sNext:       "Suivant",
                    sLast:       "Dernier"
                }
            }
        });
    }

// Exemple suppression
    function supprimer(id) {
        if (confirm("Voulez-vous vraiment supprimer cet élément ?")) {
            window.location.href = "<?= base_url('Commandes/delete/'); ?>" + id;
        }
    }
</script>


<script>
 $(document).ready(function(){
    liste_livre();
});
 function liste_livre() {
    var url = "<?= base_url() ?>stock_matieres/Stock_matieres/listing_reception";
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
    $(document).on('submit', '#FormData', function (e) {

        let motif = $(this).find('#MOTIF').val().trim();

        if (motif === '') {
        e.preventDefault(); // empêche l'envoi
        $('#erreurMotif').html('Veuillez saisir le motif avant de continuer.');
        $(this).find('#MOTIF').focus();
        return false;
    }
});
</script>


<script>
  function get_histo(id) {
      // alert(id)
    // Ouvrir le modal Bootstrap
    $('#modalColonnes').modal('show');

    // Attendre que le modal soit visible
    $('#modalColonnes').on('shown.bs.modal', function () {

      var url = "<?= base_url() ?>stock_matieres/Stock_matieres/get_details";
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



</body>
</html>
