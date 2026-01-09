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
                    <option value="<?= $type['ID_TYPE_MATIERE']?>" <?= set_value('ID_TYPE_MATIERE') == $type['ID_TYPE_MATIERE'] ? 'selected' : '' ?>><?= $type['DESCRIPTION'].'('.$type['CARACTERISTIQUE'].')' ?></option> 
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

  testtttt tabs 222
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
</script>
</body>
</html>
