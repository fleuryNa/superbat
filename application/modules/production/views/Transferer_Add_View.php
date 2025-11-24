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
            <form id="FormData" action="<?php echo base_url()?>production/Production/save_transfer" method="POST" enctype="multipart/form-data">
              <div class="row">
               <div class="col-sm-2 form-group">
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
                <label class="form-control-label">Type de Toles</label>
                <select class="form-control select2_demo_1" id="ID_TYPE_TOLES" name="ID_TYPE_TOLES">
                  <option value="">--select--</option>
                  <?php foreach($type_toles as $type){?>
                    <option value="<?= $type['ID_TYPE_TOLES']?>" <?= set_select('ID_TYPE_TOLES',$type['ID_TYPE_TOLES']) ?>><?= $type['DESCRIPTION_TOLES']; ?></option>
                  <?php }?> 
                </select>
                <font color='red' id="errorID_TYPE_TOLES"></font>
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

              <div class="col-sm-3 form-group" id="div_toles_colis" style="display:none;">
                <label>Numero de colis <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="NUMERO_COLIS" name="NUMERO_COLIS" value="<?=set_value('NUMERO_COLIS')?>">
                <font color='red' id="errorNUMERO_COLIS"></font>
              </div>


              <div class="col-sm-2 form-group" >
                <label>Quantite<span class="text-danger">*</span></label>
                <input class="form-control" type="number" id="QUANTITE" name="QUANTITE" value="<?=set_value('QUANTITE')?>">
                <font color='red' id="errorQUANTITE"></font>
              </div>

              <div class="col-sm-2 form-group">
                <label></label>
                <button class="btn btn-success btn-block" type="button" name="btnadd" id="btnadd" >ajouter</button>
              </div>

            </div>

            <div id="divdata" style="margin-left: 0px;width: auto;"></div>


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
  $(document).ready(function(){

   $('#btnadd').click(function(){

    var ID_TYPE_PRODUIT = $('#ID_TYPE_PRODUIT').val();
    var QUANTITE = $('#QUANTITE').val();
    var ID_TYPE_MATIERE = "";
    var NUMERO_COLIS = 0;

    // déterminer le bon type de matière
    if(ID_TYPE_PRODUIT == "1"){ // TOLES
      ID_TYPE_MATIERE = $('#ID_TYPE_TOLES').val();
      NUMERO_COLIS = $('#NUMERO_COLIS').val();
    } 
    else if(ID_TYPE_PRODUIT == "2"){ // CLOUS
      ID_TYPE_MATIERE = $('#ID_TYPE_CLOUS').val();
      NUMERO_COLIS = 0;
    }

    // validations
    if(ID_TYPE_MATIERE == ""){
      alert("Veuillez choisir un type de matière.");
      return;
    }

    if(QUANTITE == ""){
      alert("Veuillez saisir une quantité.");
      return;
    }

    // AJOUT AU PANIER
    $.post("<?= base_url('production/Production/addcart'); ?>", 
    {
      ID_TYPE_PRODUIT: ID_TYPE_PRODUIT,
      ID_TYPE_MATIERE: ID_TYPE_MATIERE,
      QUANTITE: QUANTITE,
      NUMERO_COLIS: NUMERO_COLIS
    },
    function(resp){
      $('#divdata').html(resp);

      console.log(resp)
    });

    $('#QUANTITE').val('');
  });


 });


  function remove_ct(id_row){

    var rowid=$('#rowid'+id_row).val();
    $.post('<?php echo base_url();?>production/Production/remove_cart',
    {
      rowid:rowid

    },
    function(data)
    {
      $('#divdata').html(data);
    });

  }

</script>

<script>
  $(document).ready(function() {
    $('.select2_demo_1').select2({
      width: '100%',
      placeholder: "Sélectionnez un élément",
      allowClear: true
    });
  });
</script>

<script>

 function choixProduit() {
  let type = $("#ID_TYPE_PRODUIT").val();

  if (type == "1") { 
      // Tôles
    $("#div_toles").show();
    $("#div_toles_colis").show();
    
    $("#div_clous").hide();

      // reset champs clous
    $("#ID_TYPE_CLOUS").val('');  
  }
  else if (type == "2") { 
      // Clous
    $("#div_clous").show();
    $("#div_toles_colis").hide();

    $("#div_toles").hide();

      // reset champs toles
    $("#ID_TYPE_TOLES").val(''); 
    $("#NUMERO_COLIS").val("");
  }
  else {
      // Rien sélectionné
    $("#div_toles").hide();
    $("#div_clous").hide();
    $("#div_toles_colis").hide();

    $("#ID_TYPE_CLOUS").val('');
    $("#QUANTITE").val("");
    $("#NUMERO_COLIS").val("");

    $("#ID_TYPE_TOLES").val('');

  }
}
</script>


</body>
</html>
