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

          <?php include 'includes/menu_production.php'; ?>

          <div class="ibox-body">
            <!-- Formulaire principal -->
            <form id="FormData" action="<?php echo base_url()?>production/Production/add" method="POST" enctype="multipart/form-data">
              <div class="row">

               <div class="col-sm-6 form-group">
                <label class="form-control-label">Type produit</label>
                <select class="form-control " id="ID_TYPE_PRODUIT" name="ID_TYPE_PRODUIT" onchange="choixProduit()">
                  <option value="">--select--</option>
                  <option value="1" <?= set_select('ID_TYPE_PRODUIT','1')?>>Toles</option>
                  <option value="2" <?= set_select('ID_TYPE_PRODUIT','2')?>>Clous</option>
                </select>
                <font color='red' id="errorID_TYPE_PRODUIT"></font>
              </div>

              <!-- DIV TOLES -->
              <div class="col-sm-6 form-group" id="div_toles" style="display:none;">
               <div class="d-flex justify-content-between align-items-center">
                <label class="form-control-label mb-0">Type de Toles</label>
                <button type="button" class="btn btn-success btn-sm rounded-circle" data-toggle="modal" data-target="#modaltoles">
                  <i class="fa fa-plus"></i>
                </button>
              </div>

              <select class="form-control select2_demo_1" id="ID_TYPE_TOLES" name="ID_TYPE_TOLES">
                <option value="">--select--</option>
                <?php foreach($type_toles as $type){?>
                <option value="<?= $type['ID_TYPE_TOLES']?>" <?= set_select('ID_TYPE_TOLES',$type['ID_TYPE_TOLES']) ?>><?= $type['DESCRIPTION_TOLES']; ?></option>
                <?php }?> 
              </select>
              <font color='red' id="errorID_TYPE_TOLES"></font>
            </div>

            <!-- DIV CLOUS -->
            <div class="col-sm-6 form-group" id="div_clous" style="display:none;">
              <div class="d-flex justify-content-between align-items-center">
                <label class="form-control-label">Type de matieres</label>
                <button type="button" class="btn btn-success btn-sm rounded-circle" data-toggle="modal" data-target="#modalclous">
                  <i class="fa fa-plus"></i>
                </button>
              </div>
              <select class="form-control select2_demo_1" id="ID_TYPE_CLOUS" name="ID_TYPE_CLOUS">
                <option value="">--select--</option>
                <?php foreach($type_clous as $type){?>
                <option value="<?= $type['ID_TYPE_CLOUS']?>" <?= set_select('ID_TYPE_CLOUS',$type['ID_TYPE_CLOUS']) ?>><?= $type['DESCRIPTION']; ?></option> 
                <?php }?> 
              </select>
              <font color='red' id="errorID_TYPE_CLOUS"></font>
            </div>

            <!-- Champs spécifiques TÔLES -->
            <div class="col-sm-6 form-group" id="div_toles_colis" style="display:none;">
              <label>Numero de colis <span class="text-danger">*</span></label>
              <input class="form-control" type="text" id="NUMERO_COLIS" name="NUMERO_COLIS" value="<?=set_value('NUMERO_COLIS')?>">
              <font color='red' id="errorNUMERO_COLIS"></font>
            </div>

            <div class="col-sm-6 form-group" id="div_toles_couleur" style="display:none;">
              <label>Couleur <span class="text-danger">*</span></label>
              <input class="form-control" type="text" id="COULEUR" name="COULEUR" value="<?=set_value('COULEUR')?>">
              <font color='red' id="errorCOULEUR"></font>
            </div>

            <div class="col-sm-6 form-group" id="div_toles_longueur" style="display:none;">
              <label>Longueur <span class="text-danger">*</span></label>
              <input class="form-control" type="text" id="LONGUEUR" name="LONGUEUR" value="<?=set_value('LONGUEUR')?>">
              <font color='red' id="errorLONGUEUR"></font>
            </div>

            <div class="col-sm-6 form-group" id="div_toles_bande" style="display:none;">
              <label>Nbre de Bande <span class="text-danger">*</span></label>
              <input class="form-control" type="text" id="NOMBRE_BANDE" name="NOMBRE_BANDE" value="<?=set_value('NOMBRE_BANDE')?>">
              <font color='red' id="errorNOMBRE_BANDE"></font>
            </div>

            <div class="col-sm-6 form-group" id="div_toles_bonet" style="display:none;">
              <label>Nbre des toles <span class="text-danger">*</span></label>
              <input class="form-control" type="text" id="NOMBRE_BONNET" name="NOMBRE_BONNET" value="<?=set_value('NOMBRE_BONNET')?>">
              <font color='red' id="errorNOMBRE_BONNET"></font>
            </div>

            <!-- Champ spécifique CLOUS -->
            <div class="col-sm-6 form-group" style="display:none;" id="div_clous_qte">
              <label>Quantite<span class="text-danger">*</span></label>
              <input class="form-control" type="number" id="QUANTITE" name="QUANTITE" value="<?=set_value('QUANTITE')?>">
              <font color='red' id="errorQUANTITE"></font>
            </div>

          </div> <!-- fin row -->

          <!-- Buttons : Ajouter au panier + Submit -->
          <div class="form-group d-flex">
            <button type="button" class="btn btn-primary mr-2" id="btnAddCart" onclick="addToCart()">Ajouter au panier</button>
            <button class="btn btn-success" type="submit">Enregistrer (submit final)</button>
          </div>

          <!-- Hidden input qui contiendra le cart JSON -->
          <input type="hidden" name="cart_data" id="cart_data" value="">

          <!-- TABLEAU PANIER -->
          <div class="table-responsive mt-4">
            <table class="table table-bordered" id="cartTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Type produit</th>
                  <th>Détails</th>
                  <th>Quantité</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <p id="cartEmptyNotice" class="text-muted" style="display:none;">Le panier est vide.</p>
          </div>

        </form>
      </div>
    </div>

    <!-- MODAL TOLES -->
    <div class="modal fade" id="modaltoles">
      <div class="modal-dialog">
        <form id="formAddToles" method="POST" onsubmit="addTypeToles();return false" accept-charset="utf-8" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Ajout d'un nouveau type de tôles</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
               <div class="form-group col-md-12">
                <label>Type de toles <i class="text-danger"> *</i></label>
                <input type="text" name="DESCRIPTION_TOLES" class="form-control" id="DESCRIPTION_TOLES" required>
                <?php echo form_error('DESCRIPTION_TOLES', '<div class="text-danger">', '</div>'); ?>
              </div>
            </div>                                    
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            <input type="submit" value="Enregistrer" class="btn btn-primary"/>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL CLOUS -->
  <div class="modal fade" id="modalclous">
    <div class="modal-dialog">
      <form id="formAddClous" method="POST" onsubmit="addTypeClous();return false" accept-charset="utf-8" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Ajout d'un nouveau type de clous</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
             <div class="form-group col-md-12">
              <label>Type de Clous <i class="text-danger"> *</i></label>
              <input type="text" name="DESCRIPTION" class="form-control" id="DESCRIPTION" required>
              <?php echo form_error('DESCRIPTION', '<div class="text-danger">', '</div>'); ?>
            </div>
          </div>                                    
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
          <input type="submit" value="Enregistrer" class="btn btn-primary"/>
        </div>
      </div>
    </form>
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
  // initialisation select2
  $(document).ready(function() {
    $('.select2_demo_1').select2({
      width: '100%',
      placeholder: "Sélectionnez un élément",
      allowClear: true
    });

    // initialiser l'affichage selon la value courante
    choixProduit();
    renderCart();
  });

  function choixProduit() {
    let type = $("#ID_TYPE_PRODUIT").val();

    if (type == "1") { 
      // Tôles
      $("#div_toles").show();
      $("#div_toles_colis").show();
      $("#div_toles_couleur").show();
      $("#div_toles_longueur").show();
      $("#div_toles_bande").show();
      $("#div_toles_bonet").show();

      $("#div_clous").hide();
      $("#div_clous_qte").hide();

      // reset champs clous
      $("#ID_TYPE_CLOUS").val(null).trigger('change'); 
      $("#QUANTITE").val(""); 
    }
    else if (type == "2") { 
      // Clous
      $("#div_clous").show();
      $("#div_clous_qte").show();

      $("#div_toles").hide();
      $("#div_toles_colis").hide();
      $("#div_toles_couleur").hide();
      $("#div_toles_longueur").hide();
      $("#div_toles_bande").hide();
      $("#div_toles_bonet").hide();

      // reset champs toles
      $("#ID_TYPE_TOLES").val(null).trigger('change'); 
      $("#NUMERO_COLIS").val("");
      $("#COULEUR").val("");
      $("#LONGUEUR").val("");
      $("#NOMBRE_BANDE").val("");
      $("#NOMBRE_BONNET").val("");
    }
    else {
      // Rien sélectionné
      $("#div_toles").hide();
      $("#div_clous").hide();
      $("#div_toles_colis").hide();
      $("#div_toles_couleur").hide();
      $("#div_toles_longueur").hide();
      $("#div_toles_bande").hide();
      $("#div_toles_bonet").hide();
      $("#div_clous_qte").hide();

      $("#ID_TYPE_CLOUS").val(null).trigger('change');
      $("#QUANTITE").val("");

      $("#ID_TYPE_TOLES").val(null).trigger('change');
      $("#NUMERO_COLIS").val("");
      $("#COULEUR").val("");
      $("#LONGUEUR").val("");
      $("#NOMBRE_BANDE").val("");
      $("#NOMBRE_BONNET").val("");
    }
  }

  /* -------------------------
     Gestion du panier (cart)
     stocké côté client en variable JS "cart"
     ------------------------- */
  let cart = [];

  function addToCart() {
    let typeProduit = $("#ID_TYPE_PRODUIT").val();
    if (!typeProduit) {
      alert("Veuillez sélectionner le type de produit !");
      return;
    }

    if (typeProduit == "1") {
      // Tôles
      let idType = $("#ID_TYPE_TOLES").val();
      let designation = $("#ID_TYPE_TOLES option:selected").text();
      let colis = $("#NUMERO_COLIS").val();
      let couleur = $("#COULEUR").val();
      let longueur = $("#LONGUEUR").val();
      let bande = $("#NOMBRE_BANDE").val();
      let bonnet = $("#NOMBRE_BONNET").val();

      if (!idType || !colis || !couleur || !longueur) {
        alert("Veuillez remplir tous les champs obligatoires des tôles (type, colis, couleur, longueur).");
        return;
      }

      let item = {
        type: "Tôles",
        id_type: idType,
        designation: designation,
        colis: colis,
        couleur: couleur,
        longueur: longueur,
        bande: bande,
        bonnet: bonnet,
        quantite: bonnet ? parseFloat(bonnet) : 1
      };

      cart.push(item);
    }
    else if (typeProduit == "2") {
      // Clous
      let idType = $("#ID_TYPE_CLOUS").val();
      let designation = $("#ID_TYPE_CLOUS option:selected").text();
      let quantite = $("#QUANTITE").val();

      if (!idType || !quantite) {
        alert("Veuillez remplir tous les champs obligatoires des clous (type, quantité).");
        return;
      }

      let item = {
        type: "Clous",
        id_type: idType,
        designation: designation,
        quantite: parseFloat(quantite)
      };

      cart.push(item);
    }

    // réinitialiser certains champs pour la prochaine saisie
    // (on garde le type de produit sélectionné pour faciliter les ajouts successifs)
    $("#NUMERO_COLIS, #COULEUR, #LONGUEUR, #NOMBRE_BANDE, #NOMBRE_BONNET, #QUANTITE").val("");
    renderCart();
  }

  function renderCart() {
    let $tbody = $("#cartTable tbody");
    $tbody.empty();

    if (cart.length === 0) {
      $("#cartEmptyNotice").show();
      $("#cartTable").hide();
      return;
    }
    $("#cartEmptyNotice").hide();
    $("#cartTable").show();

    cart.forEach((item, index) => {
      let details = "";
      if (item.type === "Tôles") {
        details = `
          <strong>${escapeHtml(item.designation)}</strong><br>
          Colis: ${escapeHtml(item.colis)}<br>
          Couleur: ${escapeHtml(item.couleur)}<br>
          Longueur: ${escapeHtml(item.longueur)}<br>
          Bande: ${escapeHtml(item.bande)}<br>
          Nbre tôles: ${escapeHtml(item.bonnet)}
        `;
      } else {
        details = `<strong>${escapeHtml(item.designation)}</strong>`;
      }

      let row = `<tr>
        <td>${index+1}</td>
        <td>${escapeHtml(item.type)}</td>
        <td>${details}</td>
        <td>${escapeHtml(String(item.quantite))}</td>
        <td>
          <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})"><i class="fa fa-trash"></i></button>
        </td>
      </tr>`;

      $tbody.append(row);
    });

    // mettre à jour le hidden pour l'envoi
    $("#cart_data").val(JSON.stringify(cart));
  }

  function removeItem(index) {
    if (index < 0 || index >= cart.length) return;
    cart.splice(index, 1);
    renderCart();
  }

  // sécurité simple pour éviter injection dans le tableau (affichage seulement)
  function escapeHtml(text) {
    if (!text && text !== 0) return '';
    return String(text)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  // s'assurer que lors du submit final, on a bien le cart JSON dans hidden
  $("#FormData").on('submit', function(e){
    // si besoin, vous pouvez vérifier que cart n'est pas vide avant submit
    // Exemple : empêcher l'envoi si panier vide
    if (cart.length === 0) { alert('Le panier est vide. Ajoutez au moins une ligne.'); e.preventDefault(); return; }

    $("#cart_data").val(JSON.stringify(cart));
    // le submit se poursuit normalement
  });

  /* -------------------------
     AJAX pour ajouter un nouveau type (tôles / clous)
     On attend côté serveur (production/Production/addTypeToles) de renvoyer
     le HTML complet des <option> pour #ID_TYPE_TOLES (ou #ID_TYPE_CLOUS)
     ------------------------- */
  function addTypeToles(){
    var DESCRIPTION_TOLES= $('#DESCRIPTION_TOLES').val().trim();
    if(DESCRIPTION_TOLES == '') { alert('Renseignez le type de tôles'); return; }

    $.post('<?php echo base_url('production/Production/addTypeToles')?>',
    {
      DESCRIPTION_TOLES:DESCRIPTION_TOLES
    },
    function(data){
      // on suppose que 'data' contient les <option> du select à remplacer
      // remplacer le HTML et ré-initialiser select2
      $('#ID_TYPE_TOLES').html(data);
      $('#ID_TYPE_TOLES').trigger('change.select2'); // refresh select2
    }).fail(function(){
      alert('Erreur lors de l\'ajout du type de tôles.');
    }).always(function(){
      $('#modaltoles').modal('hide');
      $('#DESCRIPTION_TOLES').val('');
    });
  }

  function addTypeClous(){
    var DESCRIPTION= $('#DESCRIPTION').val().trim();
    if(DESCRIPTION == '') { alert('Renseignez le type de clous'); return; }

    $.post('<?php echo base_url('production/Production/addTypeClous')?>',
    {
      DESCRIPTION:DESCRIPTION
    },
    function(data){
      $('#ID_TYPE_CLOUS').html(data);
      $('#ID_TYPE_CLOUS').trigger('change.select2');
    }).fail(function(){
      alert('Erreur lors de l\'ajout du type de clous.');
    }).always(function(){
      $('#modalclous').modal('hide');
      $('#DESCRIPTION').val('');
    });
  }

</script>

</body>
</html>
