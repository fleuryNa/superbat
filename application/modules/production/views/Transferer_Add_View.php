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
          include 'includes/menu_transferer.php';
          ?>
          <div class="ibox-body">
           <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="fa fa-check-circle"></i>
              <?= $this->session->flashdata('success'); ?>
              <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fa fa-exclamation-triangle"></i>
              <?= $this->session->flashdata('error'); ?>
              <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
              </button>
            </div>
          <?php endif; ?>
          <form id="FormData" action="<?php echo base_url()?>production/Transferer/save_transfer" method="POST" enctype="multipart/form-data">
            <div class="row">
             <div class="col-sm-3 form-group">
              <label class="form-control-label">Type produit</label>
              <select class="form-control " id="ID_TYPE_PRODUIT" name="ID_TYPE_PRODUIT" onchange="choixProduit()">
                <option value="">--select--</option>
                <option value="1" <?= set_select('ID_TYPE_PRODUIT','1')?>>Toles</option>
                <option value="2" <?= set_select('ID_TYPE_PRODUIT','2')?>>Clous</option>
              </select>
              <font color='red' id="errorID_TYPE_PRODUIT"></font>
            </div>

            <!-- DIV TOLES -->
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
          </div>


          <!-- DIV CLOUS -->
          <div class="col-sm-3 form-group" id="div_clous" style="display:none;">
            <label class="form-control-label">Type de Clous <span class="text-danger">*</span></label>
            <select class="form-control select2_demo_1" id="ID_TYPE_CLOUS" name="ID_TYPE_CLOUS">
              <option value="">--select--</option>
              <?php foreach($type_clous as $type){?>
                <option value="<?= $type['ID_TYPE_CLOUS']?>" <?= set_select('ID_TYPE_CLOUS',$type['ID_TYPE_CLOUS']) ?>><?= $type['DESCRIPTION']; ?></option> 
              <?php }?> 
            </select>
            <font color='red' id="errorID_TYPE_CLOUS"></font>
          </div>


          <div class="col-sm-2 form-group" >
            <label>Quantite<span class="text-danger">*</span></label>
            <input class="form-control" type="number" id="QUANTITE" name="QUANTITE" value="<?=set_value('QUANTITE')?>">
            <font color='red' id="errorQUANTITE"></font>
          </div>

          <div class="col-sm-2 form-group">
            <label></label>
            <button class="btn btn-info btn-block" type="button" name="btnadd" id="btnadd" >ajouter</button>
          </div>

        </div>

        <div class="table-responsive mt-3" id="divdata">
          <table class="table table-bordered table-striped" id="cartTable">
            <thead>
              <tr>
                <th>Produit</th>
                <th>Type / Colis</th>
                <th>Couleur</th>
                <th>Longueur</th>
                <th>Quantité</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Contenu dynamique via AJAX -->
            </tbody>
          </table>
        </div>
        <div class="form-group">
          <button class="btn btn-info btn-block" type="submit">Enregitrere </button>
        </div>
      </form>
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
  $(document).ready(function () {

    /* =========================
     * INIT
     ========================= */
    $('.select2_demo_1').select2({
      width: '100%',
      placeholder: "Sélectionnez un élément",
      allowClear: true
    });

    refreshCart();

    /* =========================
     * FUNCTIONS
     ========================= */
    function refreshCart() {
      $.getJSON("<?= base_url('production/Transferer/get_cart'); ?>", function (data) {

        let html = '';

        if (data.length === 0) {
          html = `<tr>
                      <td colspan="6" class="text-center text-muted">
                        Panier vide
                      </td>
        </tr>`;
      } else {
        $.each(data, function (i, item) {
          html += `
                <tr>
                    <td>${item.type_produit}</td>
                    <td>${item.type_matiere} ${item.numero_colis ? '('+item.numero_colis+')' : ''}</td>
                    <td>${item.couleur}</td>
                    <td>${item.longueur}</td>
                    <td>
                        <input type="number"
                               class="form-control cart-qty"
                               min="1"
                               data-rowid="${item.rowid}"
                               value="${item.quantite}">
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm btn-remove"
                                data-rowid="${item.rowid}">
                            Supprimer
                        </button>
                    </td>
          </tr>`;
        });
      }

      $('#cartTable tbody').html(html);
    });
    }


    function resetToles() {
      $('#ID_TYPE_TOLES').val('').trigger('change');
      $('#COULEUR, #LONGUEUR, #NUMERO_COLIS').val('');
    }

    function resetClous() {
      $('#ID_TYPE_CLOUS').val('').trigger('change');
    }

    function showError(msg) {
        alert(msg); // tu peux remplacer par toastr ou swal
      }

    /* =========================
     * CHOIX PRODUIT
     ========================= */
      $('#ID_TYPE_PRODUIT').on('change', function () {
        let type = $(this).val();

        if (type === "1") { // TOLES
          $('#div_toles').slideDown();
          $('#div_clous').hide();
          resetClous();
        } 
        else if (type === "2") { // CLOUS
          $('#div_clous').slideDown();
          $('#div_toles').hide();
          resetToles();
        } 
        else {
          $('#div_toles, #div_clous').hide();
          resetToles();
          resetClous();
        }
      });

    /* =========================
     * TOLES DATA
     ========================= */
      $('#ID_TYPE_TOLES').on('change', function () {
        let option = $(this).find(':selected');

        $('#NUMERO_COLIS').val(option.data('colis') || '');
        $('#COULEUR').val(option.data('couleur') || '');
        $('#LONGUEUR').val(option.data('longueur') || '');
      });

    /* =========================
     * ADD TO CART
     ========================= */
      $('#btnadd').on('click', function () {

        let ID_TYPE_PRODUIT = $('#ID_TYPE_PRODUIT').val();
        let QUANTITE = parseInt($('#QUANTITE').val(), 10);
        let payload = {};

        if (!ID_TYPE_PRODUIT) {
          showError("Veuillez sélectionner le type de produit.");
          return;
        }

        if (!QUANTITE || QUANTITE <= 0) {
          showError("Veuillez saisir une quantité valide.");
          return;
        }

    if (ID_TYPE_PRODUIT === "1") { // TOLES
      let ID_TYPE_MATIERE = $('#ID_TYPE_TOLES').val();
      if (!ID_TYPE_MATIERE) {
        showError("Veuillez choisir un type de tôle.");
        return;
      }

      payload = {
        ID_TYPE_PRODUIT,
        ID_TYPE_MATIERE,
        QUANTITE,
        NUMERO_COLIS: $('#NUMERO_COLIS').val(),
        COULEUR: $('#COULEUR').val(),
        LONGUEUR: $('#LONGUEUR').val()
      };
    }

    if (ID_TYPE_PRODUIT === "2") { // CLOUS
      let ID_TYPE_MATIERE = $('#ID_TYPE_CLOUS').val();
      if (!ID_TYPE_MATIERE) {
        showError("Veuillez choisir un type de clou.");
        return;
      }

      payload = {
        ID_TYPE_PRODUIT,
        ID_TYPE_MATIERE,
        QUANTITE
      };
    }

    $('#btnadd').prop('disabled', true);

    $.ajax({
      url: "<?= base_url('production/Transferer/addcart'); ?>",
      type: "POST",
      dataType: "json",
      data: payload,
      success: function (res) {
        if (res.status) {
          refreshCart();
          $('#QUANTITE').val('');
        } else {
          showError(res.message || "Erreur lors de l'ajout.");
        }
      },
      error: function () {
        showError("Erreur serveur.");
      },
      complete: function () {
        $('#btnadd').prop('disabled', false);
      }
    });
  });

    /* =========================
     * CART ACTIONS
     ========================= */
      $(document).on('click', '.btn-remove', function () {
        let rowid = $(this).data('rowid');

        $.post("<?= base_url('production/Transferer/remove_cart'); ?>", { rowid }, function (res) {
          if (res.status) {
            refreshCart();
          } else {
            showError("Impossible de supprimer.");
          }
        }, 'json');
      });


      $(document).on('change', '.cart-qty', function () {

        let rowid = $(this).data('rowid');
        let qty = parseInt($(this).val(), 10);

        if (!qty || qty <= 0) {
          showError("Quantité invalide.");
          refreshCart();
          return;
        }

        $.post(
          "<?= base_url('production/Transferer/update_cart'); ?>",
          { rowid, QUANTITE: qty },
          function (res) {
            if (res.status) {
              refreshCart();
            } else {
              showError("Échec de mise à jour.");
            }
          },
          'json'
          );
      });


    /* =========================
     * ALERT AUTO HIDE
     ========================= */
      setTimeout(function () {
        $('.alert').fadeOut('slow');
      }, 4000);

    });
  </script>


</body>
</html>
