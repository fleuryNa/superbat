<!DOCTYPE html>
<html lang="fr">
<?php include VIEWPATH.'includes/header.php'; ?>

<body class="fixed-navbar">
  <div class="page-wrapper">
    <?php include VIEWPATH.'includes/navbar.php'; ?>
    <?php include VIEWPATH.'includes/sidebarMenu.php'; ?>

    <div class="content-wrapper">
      <!-- PAGE HEADING -->
      <div class="page-heading">
        <h1 class="page-title"><?= $title; ?></h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#"><i class="la la-home font-20"></i></a></li>
          <li class="breadcrumb-item"><?= $title; ?></li>
        </ol>
      </div>

      <!-- PAGE CONTENT -->
      <div class="page-content fade-in-up">
        <div class="ibox">
          <?php include 'includes/menu_vente_produits.php'; ?>

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

            <form id="FormData" method="POST">

              <!-- ================= CLIENT ================= -->
              <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                  <h4 class="mb-0">Informations du client</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 form-group">
                      <label>Nom <span class="text-danger">*</span></label>
                      <input class="form-control" type="text" id="NOM" name="NOM" value="<?=set_value('NOM')?>">
                    </div>
                    <div class="col-md-4 form-group">
                      <label>Téléphone <span class="text-danger">*</span></label>
                      <input class="form-control" name="TELEPHONE" id="TELEPHONE" value="<?=set_value('TELEPHONE')?>">
                    </div>
                    <div class="col-md-4 form-group">
                      <label>NIF </label>
                      <input class="form-control" name="NIF" id="NIF" value="<?=set_value('NIF')?>">
                    </div>
                    <div class="col-sm-4 form-group">
                      <label>Province <span class="text-danger">*</span></label>
                      <select class="form-control select2_demo_1" id="PROVINCE_ID" name="PROVINCE_ID" onchange="get_commune(this)">
                        <option value="">--select--</option>
                        <?php foreach($provinces as $type){ ?>
                          <option value="<?= $type['PROVINCE_ID']?>" <?= set_value('PROVINCE_ID') == $type['PROVINCE_ID'] ? 'selected' : '' ?>><?= $type['PROVINCE_NAME'] ?></option> 
                        <?php }?> 
                      </select>
                    </div>
                    <div class="col-sm-4 form-group">
                      <label>Commune <span class="text-danger">*</span></label>
                      <select class="form-control select2_demo_1" id="COMMUNE_ID" name="COMMUNE_ID" onchange="get_zone(this)">
                        <option value="">--select--</option>
                      </select>
                    </div>
                    <div class="col-sm-4 form-group">
                      <label>Zone <span class="text-danger">*</span></label>
                      <select class="form-control select2_demo_1" id="ZONE_ID" name="ZONE_ID" onchange="get_colline(this)">
                        <option value="">--select--</option>
                      </select>
                    </div>
                    <div class="col-sm-4 form-group">
                      <label>Colline <span class="text-danger">*</span></label>
                      <select class="form-control select2_demo_1" id="COLLINE_ID" name="COLLINE_ID">
                        <option value="">--select--</option>
                      </select>
                    </div>
                    <div class="col-md-4 form-group">
                      <label>Adresse <span class="text-danger">*</span></label>
                      <input class="form-control" name="ADRESSE_COMPLETE" id="ADRESSE_COMPLETE" value="<?=set_value('ADRESSE_COMPLETE')?>">
                    </div>
                    <div class="col-md-4 form-group">
                      <label>Assujetti</label><br>
                      <label class="ui-radio ui-radio-inline">
                        <input type="radio" name="ASSUJETI" value="1" <?= set_value('ASSUJETI') == '1' ? 'checked' : '' ?>> 
                        <span class="input-span"></span> Oui
                      </label>
                      <label class="ui-radio ui-radio-inline">
                        <input type="radio" name="ASSUJETI" value="2" <?= set_value('ASSUJETI') == '2' ? 'checked' : '' ?>> 
                        <span class="input-span"></span> Non
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- ================= PRODUITS ================= -->
              <div class="card mb-4">
                <div class="card-header bg-success text-white">
                  <h4 class="mb-0">Ajout des produits</h4>
                </div>
                <div class="card-body">
                  <div class="row align-items-end">
                    <div class="col-md-3 form-group">
                      <label>Type produit *</label>
                      <select class="form-control" id="ID_TYPE_PRODUIT" onchange="choixProduit()">
                        <option value="">-- Sélectionner --</option>
                        <option value="1">Tôles</option>
                        <option value="2">Clous</option>
                      </select>
                    </div>

                    <div class="col-sm-3 form-group" id="div_toles" style="display:none;">
                      <label class="form-control-label">Type de Tôles</label>

                      <select class="form-control select2_demo_1" id="ID_TYPE_TOLES" name="ID_TYPE_TOLES">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($type_toles as $type) { ?>
                          <option
                          value="<?= $type['ID_TYPE_TOLES']; ?>"
                          data-colis="<?= $type['NUMERO_COLIS']; ?>"
                          data-couleur="<?= $type['COULEUR']; ?>"
                          data-longueur="<?= $type['LONGUEUR']; ?>"
                          data-descriptiontole="<?= $type['DESCRIPTION_TOLES']; ?>"
                          <?= set_select('ID_TYPE_TOLES', $type['ID_TYPE_TOLES']); ?>
                          >
                          <?= $type['DESCRIPTION_TOLES'].' ('.$type['NUMERO_COLIS'].')'; ?>
                        </option>
                      <?php } ?>
                    </select>

                    <font color="red" id="errorID_TYPE_TOLES"></font>

                    <!-- Champs cachés (UN SEUL exemplaire) -->
                    <input type="hidden" id="COULEUR" name="COULEUR">
                    <input type="hidden" id="LONGUEUR" name="LONGUEUR">
                    <input type="hidden" id="NUMERO_COLIS" name="NUMERO_COLIS">
                    <input type="hidden" id="DESCRIPTION_TOLES" name="DESCRIPTION_TOLES">
                  </div>


                  <div class="col-md-3 form-group" id="div_clous" style="display:none">
                    <label>Type de clous *</label>
                    <select class="form-control select2_demo_1" id="ID_TYPE_CLOUS">
                      <option value="">-- Sélectionner --</option>
                      <?php foreach($type_clous as $c){ ?>
                        <option 
                        value="<?= $c['ID_TYPE_CLOUS']?>"
                        data-desciptionclous="<?= $c['DESCRIPTION']; ?>"
                        ><?= $c['DESCRIPTION']?></option>

                        <input type="hidden" id="DESCRIPTION" name="DESCRIPTION">
                      <?php } ?>
                    </select>
                  </div>

                  <div class="col-md-2 form-group">
                    <label>Quantité *</label>
                    <input class="form-control" type="number" id="QUANTITE" value="1">
                  </div>
                  <div class="col-md-2 form-group">
                    <label>Prix unitaire *</label>
                    <input class="form-control" type="number" id="PRIX_UNITAIRE" value="0">
                  </div>
                  <div class="col-md-2 form-group">
                    <button type="button" id="btnadd" class="btn btn-success btn-block">
                      <i class="fa fa-plus"></i> Ajouter
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= PANIER ================= -->
            <div class="card">
              <div class="card-header bg-info text-white">
                <h4 class="mb-0">Produits ajoutés</h4>
              </div>
              <div class="card-body">
                <div id="divdata">
                  <div class="alert alert-info">Aucun produit dans le panier</div>
                </div>
              </div>
            </div>
            <button type="button" id="btnEnregistrer" class="btn btn-primary mt-3 col-md-12">Enregistrer la commande</button>
          </form>
        </div>
      </div>
    </div>
    <?php include VIEWPATH.'includes/footer.php'; ?>
  </div>
</div>


<?php include VIEWPATH.'includes/scripts.php'; ?>

<script>
  $('#ID_TYPE_TOLES').on('change', function(){

    let opt = $(this).find(':selected');

    let couleur  = opt.data('couleur') || '';
    let longueur = opt.data('longueur') || '';
    let colis    = opt.data('colis') || '';
    let description_toles  = opt.data('descriptiontole') || '';
    $('#COULEUR').val(couleur);
    $('#LONGUEUR').val(longueur);
    $('#NUMERO_COLIS').val(colis);
    $('#DESCRIPTION_TOLES').val(description_toles);

   console.log('description_toles',description_toles)

  });

  $('#ID_TYPE_CLOUS').on('change', function(){

    let opt = $(this).find(':selected');

    let description_clous  = opt.data('desciptionclous') || '';

    $('#DESCRIPTION').val(description_clous);

  });
</script>


<script>
  $('.select2_demo_1').select2({ width:'100%' });

  let panier = [];

// Vérification client complet
  function clientComplet(){
    return $('#NOM').val() && $('#TELEPHONE').val() && $('#PROVINCE_ID').val()
    && $('#COMMUNE_ID').val() && $('#ZONE_ID').val() && $('#COLLINE_ID').val() && $('#ADRESSE_COMPLETE').val();
  }

  function verifierProduit(type_id, matiere_id, qty, prix){
    if(!type_id){
      showAlert('danger', 'Veuillez sélectionner un type de produit.');
      return false;
    }
    if(!matiere_id){
      showAlert('danger', 'Veuillez sélectionner un type de matière.');
      return false;
    }
    if(qty <= 0){
      showAlert('danger', 'Quantité invalide.');
      return false;
    }
    if(prix <= 0){
      showAlert('danger', 'Prix unitaire invalide.');
      return false;
    }
    return true;
  }

  function showAlert(type, message, timeout = 4000){
    $('#alertBox').html(`
    <div class="alert alert-${type} alert-dismissible fade show text-center">
      ${message}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    `);
    if(timeout > 0){
      setTimeout(()=> $('#alertBox').fadeOut('slow'), timeout);
    }
  }


// Ajouter un produit
  $('#btnadd').click(function(){
    if(!clientComplet()) return showAlert('danger','Veuillez compléter les informations du client.');

    let type_id = $('#ID_TYPE_PRODUIT').val();
    let matiere_id = type_id==1 ? $('#ID_TYPE_TOLES').val() : $('#ID_TYPE_CLOUS').val();
    let qty = parseInt($('#QUANTITE').val());
    let prix = parseFloat($('#PRIX_UNITAIRE').val());
    let colis = $('#NUMERO_COLIS').val();
    let couleur = $('#COULEUR').val();
    let longueur = $('#LONGUEUR').val();
    let description = type_id==1 ? $('#DESCRIPTION_TOLES').val() : $('#DESCRIPTION').val();;

    if(!verifierProduit(type_id, matiere_id, qty, prix)) return;

  // Doublon: même type + matière + colis
    let index = panier.findIndex(p => p.type_id==type_id && p.matiere_id==matiere_id && p.colis==colis);
    if(index>=0) panier[index].qty += qty;
    else panier.push({type_id,matiere_id, qty, prix, colis, couleur, longueur,description});

    renderCart();
    $('#QUANTITE').val(1);
    $('#PRIX_UNITAIRE').val('');
    $('#ID_TYPE_TOLES, #ID_TYPE_CLOUS').val('').trigger('change');
  });



// Supprimer un produit
  function supprimerProduit(index){
    panier.splice(index,1);
    renderCart();
  }
  function renderCart() {
    const $div = $('#divdata');

    if (!Array.isArray(panier) || panier.length === 0) {
      $div.html('<div class="alert alert-info text-center">Aucun produit dans le panier</div>');
      return;
    }

    let totalGeneral = 0;

    let html = `
    <table class="table table-bordered table-sm table-hover">
      <thead class="bg-light">
        <tr>
          <th>#</th>
          <th>Produit</th>
          <th>Détails</th>
          <th>Qté</th>
          <th>P.U</th>
          <th>Total</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
    `;

    panier.forEach((p, i) => {
    // Validation qty
      p.qty = Math.max(1, parseInt(p.qty) || 1);
      console.log('gestion details',p)
      let type_libelle=p.type_id==1 ? 'Toles' : 'Clous';
    // Calcul total par ligne
      let total = (parseFloat(p.prix) || 0) * p.qty;
      totalGeneral += total;

    // Détails produit
      let details = p.description || '';
      if (p.type_id == 1) {
        details += `<br><small>
        Colis : <b>${p.colis || '-'}</b> |
        Couleur : <b>${p.couleur || '-'}</b> |
        Longueur : <b>${p.longueur || '-'}</b>
      </small>`;
    }

    html += `
      <tr>
        <td>${i+1}</td>
        <td>${type_libelle || '-'}</td>
        <td>${details}</td>
        <td>
          <input type="number" min="1" class="form-control form-control-sm"
            value="${p.qty}"
            onchange="panier[${i}].qty=Math.max(1,this.value);renderCart()">
        </td>
        <td>${(parseFloat(p.prix) || 0).toFixed(2)}</td>
        <td>${total.toFixed(2)}</td>
        <td>
          <button class="btn btn-danger btn-sm" title="Supprimer"
            onclick="if(confirm('Supprimer ce produit ?')) { panier.splice(${i},1); renderCart(); }">
            ✖
          </button>
        </td>
      </tr>
    `;
  });

    html += `
      </tbody>
    </table>
    <div class="text-right mt-2">
      <h5>Total général : <strong>${totalGeneral.toFixed(2)}</strong></h5>
    </div>
    `;

    $div.html(html);
  }



// Enregistrer la commande
  $('#btnEnregistrer').click(function(){
    if(!clientComplet()) return showAlert('danger','Veuillez compléter les informations du client.');
    if(panier.length===0) return showAlert('danger','Panier vide !');

    let client = {
      NOM: $('#NOM').val().trim(),
      TELEPHONE: $('#TELEPHONE').val().trim(),
      NIF: $('#NIF').val().trim(),
      PROVINCE_ID: $('#PROVINCE_ID').val(),
      COMMUNE_ID: $('#COMMUNE_ID').val(),
      ZONE_ID: $('#ZONE_ID').val(),
      COLLINE_ID: $('#COLLINE_ID').val(),
      ADRESSE_COMPLETE: $('#ADRESSE_COMPLETE').val().trim(),
      ASSUJETI: $('input[name="ASSUJETI"]:checked').val()
    };

    $('#btnEnregistrer').prop('disabled',true);

    $.ajax({
      url: "<?= base_url('vente/Vente_Produits/save_order') ?>",
      type:"POST",
      dataType:"json",
      data:{panier, client},
      success:function(resp){
        if(resp.status==='success'){
          showAlert('success',resp.message);
          panier=[];
          renderCart();
          $('#FormData')[0].reset();
        } else showAlert('danger',resp.message);
      },
      error:function(){ showAlert('danger','Erreur serveur. Veuillez réessayer'); },
      complete:function(){ $('#btnEnregistrer').prop('disabled',false); }
    });
  });





// Affichage dynamique selon produit
  function choixProduit(){
    let t=$('#ID_TYPE_PRODUIT').val();
    $('#div_toles,#div_clous,#div_toles_colis').hide();
    if(t==1){ $('#div_toles,#div_toles_colis').show(); }
    if(t==2){ $('#div_clous').show(); }
  }
</script>

<script>
  function get_commune(va){
    var provine_id=$(va).val();
    $('#COMMUNE_ID').html('<option>Chargement...</option>');
    $.post('<?= base_url('vente/Vente_Produits/get_commune')?>', {provine_id:provine_id}, function(data){ $('#COMMUNE_ID').html(data); });
  }
  function get_zone(va){
    var commune_id=$(va).val();
    $('#ZONE_ID').html('<option>Chargement...</option>');
    $.post('<?= base_url('vente/Vente_Produits/get_zone')?>', {commune_id:commune_id}, function(data){ $('#ZONE_ID').html(data); });
  }
  function get_colline(va){
    var zone_id=$(va).val();
    $('#COLLINE_ID').html('<option>Chargement...</option>');
    $.post('<?= base_url('vente/Vente_Produits/get_colline')?>', {zone_id:zone_id}, function(data){ $('#COLLINE_ID').html(data); });
  }



  $(document).ready(function () {
   setTimeout(function () {
    $('.alert').fadeOut('slow');
  }, 4000);
 });
</script>
</body>
</html>
