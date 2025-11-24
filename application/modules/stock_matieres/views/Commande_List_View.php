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
                        <a class="nav-link active" href="#tab-1-1" data-toggle="tab"><i class="fa fa-line-chart"></i> Commander</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab-1-2" data-toggle="tab"><i class="fa fa-heartbeat"></i> Livrer</a>
                    </li>

                </ul>

                <div class="tab-content">
                  <div class="tab-pane fade show active" id="tab-1-1"> 
                    <form action="<?= base_url('stock_matieres/Commande_production/traitement'); ?>" method="POST">

                        <table class="table table-striped table-bordered table-hover table-modern" id="mytable" width="100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Demandeur</th>
                                    <th>Type matières</th>
                                    <th>Nombre colis</th>
                                    <th>Quantité (Tonne)</th>

                                    <th>#</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($commandes as $cmd): ?>
                                    <tr>
                                        <td><?= date("d/m/Y", strtotime($cmd['DATE_INSERTION'])); ?></td>
                                        <td><?= $cmd['NOM']; ?></td>
                                        <td><?= $cmd['DESCRIPTION']; ?></td>
                                        <td class="text-center">
                                            <input 
                                            type="text" 
                                            name="nombre_colis[]" 
                                            style="width:80%;"
                                            class="form-control"
                                            value="<?= $cmd['NOMBRE_COLIS']; ?>"
                                            >
                                        </td>

                                        <td class="text-center">
                                            <input 
                                            type="text" 
                                            name="quantite_tonne[]" 
                                            style="width:80%;"
                                            class="form-control"
                                            value="<?= $cmd['QUANTITE_TONNE']; ?>"
                                            >

                                        </td>

                                         <!-- ID caché pour récupération -->
                                            <input type="hidden" name="id_commande[]" value="<?= $cmd['ID_COMANDE_PROD']; ?>">

                                        <!-- Checkbox -->
                                        <td class="text-center">
                                            <label class="ui-checkbox ui-checkbox-primary">
                                                <input type="checkbox" name="select_commande[]" value="<?= $cmd['ID_COMANDE_PROD']; ?>">
                                                <span class="input-span"></span>
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary">
                                Enregistrer la sélection
                            </button>
                        </div>

                    </form>
                </div>
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
                  <!-- Les données seront chargées dynamiquement par DataTables -->
              </tbody>
          </table></div>

      </div>
  </div>
</div>

<?php include VIEWPATH.'includes/footer.php'; ?>
</div>
</div>

<?php include VIEWPATH.'includes/settings.php'; ?>

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
