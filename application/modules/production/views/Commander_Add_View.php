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
            <form id="FormData" action="<?php echo base_url()?>production/Commander/ajouter" method="POST" enctype="multipart/form-data">
              <div class="row">


                <div class="col-sm-3 form-group">
                  <label class="form-control-label">Type de matieres</label>
                  <select class="form-control select2_demo_1" id="ID_TYPE_MATIERE" name="ID_TYPE_MATIERE">
                    <option value="">--select--</option>
                    <?php foreach($type_matieres as $type){?>
                      <option value="<?= $type['ID_TYPE_MATIERE']?>" <?= set_value('ID_TYPE_MATIERE') == $type['ID_TYPE_MATIERE'] ? 'selected' : '' ?>><?= $type['DESCRIPTION'].'('.$type['CARACTERISTIQUE'].')' ?></option> 
                    <?php }?> 
                  </select>
                  <font color='red' id="errorID_TYPE_MATIERE"></font>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Numero de colis <span class="text-danger">*</span></label>
                  <input class="form-control" type="number" class="form-control" id="NUMERO_COLIS" name="NUMERO_COLIS" value="<?=set_value('NUMERO_COLIS')?>">
                  <font color='red' id="errorNUMERO_COLIS"></font>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Quantite<span class="text-danger">*</span></label>
                  <input class="form-control" type="number" id="QUANTITE_TONNE" name="QUANTITE_TONNE" value="<?=set_value('QUANTITE_TONNE')?>">
                  <font color='red' id="errorQUANTITE_TONNE"></font>
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
<?php include VIEWPATH.'includes/settings.php'; ?>
<div class="sidenav-backdrop backdrop"></div>
<div class="preloader-backdrop">
  <div class="page-preloader">Loading</div>
</div>

<!-- SCRIPTS -->
<?php include VIEWPATH.'includes/scripts.php'; ?>


<script>
 $(document).ready(function(){

  $('#btnadd').click(function(){

    var ID_TYPE_MATIERE=$('#ID_TYPE_MATIERE').val();
    var NUMERO_COLIS=$('#NUMERO_COLIS').val();
    var QUANTITE_TONNE=$('#QUANTITE_TONNE').val();
    

    if (ID_TYPE_MATIERE=="")
    {
     $('#errorID_TYPE_MATIERE').html('Le type de document est obligatoire');
   }

   else if (ID_TYPE_MATIERE!="")
   {
     $('#errorID_TYPE_MATIERE').html(''); 
   }


   if (QUANTITE_TONNE=="")
   {
     $('#errorQUANTITE_TONNE').html('Le profil est obligatoire');
   }

   else if (QUANTITE_TONNE!="")
   {
     $('#errorQUANTITE_TONNE').html(''); 
   }

  

if(ID_TYPE_MATIERE!="" && QUANTITE_TONNE!="" ) 
{

  $.post("<?php echo base_url('production/Commander/addcart/'); ?>", 
    {ID_TYPE_MATIERE:ID_TYPE_MATIERE,QUANTITE_TONNE:QUANTITE_TONNE,NUMERO_COLIS:NUMERO_COLIS},

    function(resp){

      $('#divdata').html(resp);
      $('#ID_TYPE_MATIERE').val('');
      $('#QUANTITE_TONNE').val('');
      $('#NUMERO_COLIS').val('');

    });}

});
});


 function remove_ct(id_row){

  var rowid=$('#rowid'+id_row).val();
  $.post('<?php echo base_url();?>production/Commander/remove_cart',
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
</body>
</html>
