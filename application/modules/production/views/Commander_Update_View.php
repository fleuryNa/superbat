<?php
  include VIEWPATH.'includes/new_header.php';
  ?>
  
<!-- Site wrapper -->
<div class="wrapper">
  <?php
  include VIEWPATH.'includes/new_top_menu.php';
  include VIEWPATH.'includes/new_menu_principal.php';
  ?>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">

  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php 
   include 'includes/menu_type_doc.php';
    ?>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">

      <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Modification d'un nouveau type de document</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="FormData" action="<?php echo base_url()?>configuration/Type_Doc/update" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="DOC_TYPE_ID" id="DOC_TYPE_ID" value="<?=$typesdoc['DOC_TYPE_ID']?>">
                <div class="card-body row">
                  <div class="form-group col-lg-6">
                    <label for="exampleInputEmail1">Description <spam class="text-danger">*</spam> </label>
                    <input type="text" class="form-control" id="DESC_TYPE" name="DESC_TYPE" placeholder="Type de document" value="<?=$typesdoc['DESC_TYPE']?>">
                     <font color='red' id="errortype"></font> 
                  </div>
  
                </div>

          <!-- DEBUT CART -->
          <br>
        <table class="table table-bordered">

          <thead>
            <th> 
                <label for="exampleInputEmail1" class="col-lg-3 col-md-3 col-sm-4">Profil</label>
              </th>

              <th> 
                <label for="exampleInputEmail1" class="col-lg-3 col-md-3 col-sm-4">Actions</label>
              </th>

                 <th> 
                <label for="exampleInputEmail1" class="col-lg-3 col-md-3 col-sm-4">Numéro</label>
              </th>

          </thead>

          <tbody>
            
           <td>

        <select name="PROFIL_ID" id="PROFIL_ID" class="form-control input-sm">
            <option value="">Séléctionner</option>
              <?php foreach($profiles as $p)
              { ?>
              <option value="<?php echo $p['PROFIL_ID'] ?>" <?php echo  set_select('PROFIL_ID', $p['PROFIL_ID']); ?> <?= ($PROFIL_ID == $p['PROFIL_ID'])?"selected":"" ?> ><?php echo $p['DESCRIPTION']?></option>
                <?php
                 } ?>

              </select>
            <font color='red' id="errorprofil"></font> 
             
           </td>


          <td>

           <select name="STATUT_DOC_ID" id="STATUT_DOC_ID" class="form-control input-sm">
            <option value="">Séléctionner</option>
              <?php foreach($etapes as $etap)
              { ?>
              <option value="<?php echo $etap['STATUT_DOC_ID'] ?>" <?php echo  set_select('STATUT_DOC_ID', $etap['STATUT_DOC_ID']); ?> <?= ($STATUT_DOC_ID == $etap['STATUT_DOC_ID'])?"selected":"" ?> ><?php echo $etap['DESC_STATUT']?></option>
                <?php
                 } ?>

              </select>
            <font color='red' id="erroretape"></font> 
             
           </td>


          <td>   
          <input type="number" name="NUM" id="NUM" class="form-control col-lg-3 col-md-3 col-sm-4">
          <font color='red' id="errornum"></font> 
         </td>



          <td>
            <button type="button" name="btnadd" class="btn btn-primary" id="btnadd">Ajouter</button>
           </td>


          </tbody>


          </table>

           <div id="divcart" style="margin-left: 0px;width: auto;">
             <?=$cart?>
           </div>

          <div id="divdata" style="margin-left: 0px;width: auto;"></div>




          <!-- FIN CART -->

                <!-- /.card-body -->


              </form>
            </div>
        
        
        <!-- /.card-body -->
        <!-- <div class="card-footer">
          Footer
        </div> -->
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php
 include VIEWPATH.'includes/new_copy_footer.php';  
  ?>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<?php
  include VIEWPATH.'includes/new_script.php';
  ?>
<script>
     $(document).ready(function(){

      $('#btnadd').click(function(){

        var PROFIL_ID=$('#PROFIL_ID').val();
        var STATUT_DOC_ID=$('#STATUT_DOC_ID').val();
        var NUM=$('#NUM').val();
        var DESC_TYPE=$('#DESC_TYPE').val();
        var DOC_TYPE_ID=$('#DOC_TYPE_ID').val();

         if (DESC_TYPE=="")
         {
         $('#errortype').html('Le type de document est obligatoire');
         }

         else if (DESC_TYPE!="")
         {
         $('#errortype').html(''); 
         }


         if (PROFIL_ID=="")
         {
         $('#errorprofil').html('Le profil est obligatoire');
         }

         else if (PROFIL_ID!="")
         {
         $('#errorprofil').html(''); 
         }

         if (STATUT_DOC_ID=="")
         {
          $('#erroretape').html('L\'action est obligatoire');
         }

         else if (STATUT_DOC_ID!="")
         {
         $('#erroretape').html(''); 
         }

         if(NUM=="")
          {
           $('#errornum').html('Le num est obligatoire');
          }

          else if(NUM!="")
          {
          $('#errornum').html('');  
          }

        if(DESC_TYPE!="" && PROFIL_ID!="" && STATUT_DOC_ID!="" && Number(NUM) >0) 
        {

        $.post("<?php echo base_url('configuration/Type_Doc/addcart/'); ?>", 
          {PROFIL_ID:PROFIL_ID,STATUT_DOC_ID:STATUT_DOC_ID,NUM:NUM},

        function(resp){
     
        $('#divdata').html(resp);
        $('#PROFIL_ID').val('');
        $('#STATUT_DOC_ID').val('');
        $('#NUM').val('');
        $('#divcart').hide();

       });}

      });
     });


  function remove_ct(id_row){

  var rowid=$('#rowid'+id_row).val();
    $.post('<?php echo base_url();?>configuration/Type_Doc/remove_cart',
  {
    rowid:rowid

    },
    function(data)
    {
    $('#divdata').html(data);
    $('#divcart').hide();
    });

}

</script>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
</html>
