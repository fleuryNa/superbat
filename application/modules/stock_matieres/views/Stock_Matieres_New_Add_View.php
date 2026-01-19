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
          include 'includes/menu_stock_new.php';
          ?>

          <div class="page-content fade-in-up">
            <div class="ibox">
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
            <div class="ibox-body">
             <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link active" href="#tab-1-1" data-toggle="tab"><i class="fa fa-line-chart"></i> Simple</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#tab-1-2" data-toggle="tab"><i class="fa fa-heartbeat"></i> Avec Lot</a>
              </li>

            </ul>

            <div class="tab-content">
              <div class="tab-pane fade show active" id="tab-1-1"> 
                <div class="ibox-body">
                  <form id="FormData" action="<?php echo base_url()?>stock_matieres/Stock_Matieres_New/add" method="POST" enctype="multipart/form-data">
                    <div class="row">


                      <div class="col-sm-6 form-group">
                        <label class="form-control-label">Type de matières <span class="text-danger">*</span></label>
                        <select class="form-control select2_demo_1" id="ID_TYPE_MATIERE" name="ID_TYPE_MATIERE">
                          <option value="">--select--</option>
                          <?php foreach($type_matieres as $type){?>
                            <option value="<?= $type['ID_TYPE_MATIERE']?>" <?= set_value('ID_TYPE_MATIERE') == $type['ID_TYPE_MATIERE'] ? 'selected' : '' ?>><?= $type['DESCRIPTION'].'('.$type['UNITE'].')' ?></option> 
                          <?php }?> 
                        </select>
                        <?php echo form_error('ID_TYPE_MATIERE', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-sm-6 form-group">
                        <label>Description</label>
                        <input class="form-control" type="text" id="DESCRIPTION" name="DESCRIPTION" placeholder="Description" value="<?=set_value('DESCRIPTION')?>">

                      </div>



                      <div class="col-sm-6 form-group">
                        <label>Quantité<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="QUANTITE" name="QUANTITE" placeholder="Entrez la quantité" value="<?=set_value('QUANTITE')?>">
                        <?php echo form_error('QUANTITE', '<div class="text-danger">', '</div>'); ?>
                      </div>


                      <div class="col-sm-6 form-group">
                        <label class="form-control-label">Fournisseur </label>
                        <select class="form-control select2_demo_1" id="ID_FOURNISSEUR" name="ID_FOURNISSEUR">
                          <option value="">--select--</option>
                          <?php foreach($fournisseur as $type){?>

                            <option value="<?= $type['ID_FOURNISSEUR']?>" <?= set_value('ID_FOURNISSEUR') == $type['ID_FOURNISSEUR'] ? 'selected' : '' ?>><?= $type['NOM'].'de '.$type['LOCALITE'].')' ?></option> 
                          <?php }?> 
                        </select>

                      </div>

                      <div class="col-sm-6 form-group">
                        <label>Date d'entrée <span class="text-danger">*</span></label>
                        <input class="form-control" type="date" id="DATE_ENTREE" name="DATE_ENTREE" value="<?=set_value('DATE_ENTREE')?>">
                        <?php echo form_error('DATE_ENTREE', '<div class="text-danger">', '</div>'); ?>
                      </div>



                    </div>

                    <div class="form-group">
                      <button class="btn btn-success btn-block" type="submit">Submit</button>
                    </div>
                  </form>
                </div>

              </div>
              <div class="tab-pane" id="tab-1-2">
               <form id="FormData" action="<?php echo base_url()?>stock_matieres/Stock_Matieres_New/upload_excel" method="POST" enctype="multipart/form-data">
                <div class="row">

                                <div class="col-sm-6 form-group" data-toggle="buttons">
                               

                                    <label class="form-control-label">Provient d'un autre Lot incomplet ? </label>
                        <select class="form-control" id="is_complet" name="is_complet" onchange="affiche_lot()">

                        <option value="1" selected>Non</option>
                        <option value="2">Oui</option>
                      </select>

                    </div>
                    <div class="col-sm-6 form-group" id="lot_div" style="display: none;">
                        <label class="form-control-label">Lots </label>
                        <select class="form-control select2_demo_1" id="LOT_N" name="LOT_N">
                          <option value="">--select--</option>
                          <?php foreach($lots as $type){?>

                            <option value="<?= $type['ID_STOCK_MATIERE']?>" <?= set_value('ID_STOCK_MATIERE') == $type['ID_STOCK_MATIERE'] ? 'selected' : '' ?>><?= $type['LOT_MP'].'' ?></option> 
                          <?php }?> 
                        </select>

                      </div>
                  <div class="col-sm-6 form-group">
                    <label>PACKING LIST</label>
                    <input class="form-control" type="file" id="fichier_excel" name="fichier_excel" placeholder="" value="<?=set_value('fichier_excel')?>">

                  </div>
                  <div class="col-sm-6 form-group">
                    <label>Date d'entrée <span class="text-danger">*</span></label>
                    <input class="form-control" type="date" id="DATE_ENTREE_EXCEL" name="DATE_ENTREE_EXCEL" value="<?=set_value('DATE_ENTREE_EXCEL')?>">
                    <?php echo form_error('DATE_ENTREE_EXCEL', '<div class="text-danger">', '</div>'); ?>
                  </div>
             

                <div class="col-sm-6 form-group" data-toggle="buttons">
                               

               <label class="form-control-label">Après l'enregistrement, le Lot sera complet ? </label>
                        <select class="form-control" id="verify_complet" name="verify_complet">

                        <option value="1" selected>Oui</option>
                        <option value="2">Non</option>
                      </select>

                    </div>
                       </div>

                <div class="form-group col-sm-12">
                  <button class="btn btn-success btn-block" type="submit">Submit</button>
                </div>
              </form>
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

<!-- SETTINGS / BACKDROPS -->
<!-- <?php include VIEWPATH.'includes/settings.php'; ?> -->
<div class="sidenav-backdrop backdrop"></div>
<div class="preloader-backdrop">
  <div class="page-preloader">Loading</div>
</div>

<!-- SCRIPTS -->
<?php include VIEWPATH.'includes/scripts.php'; ?>


<script>
  document.getElementById('togglePassword').addEventListener('click', function() {
    const input = document.getElementById('PASSWORD');
    const icon = this;
    
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash'); // change l’icône
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  });
</script>

<script>
  $(document).ready(function() {
    $('.select2_demo_1').select2({
      width: '100%',
      placeholder: "Sélectionnez un élément",
      allowClear: true
    });
  });

  $(document).ready(function () {
   setTimeout(function () {
    $('.alert').fadeOut('slow');
  }, 4000);
 });
</script>

<script>
  function affiche_lot() {
    // body...
  
  var id=$("#is_complet").val();

  if (id==2) {

  document.getElementById("lot_div").style.display="block";
}else{

  document.getElementById("lot_div").style.display="none";

  }
}
</script>


</body>
</html>
