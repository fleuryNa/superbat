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
          include 'includes/menu_founisseur.php';
          ?>
          <div class="ibox-body">
            <form id="FormData" action="<?php echo base_url()?>stock_matieres/Fournisseur/update" method="POST" enctype="multipart/form-data">
              <div class="row">
                 <input class="form-control" type="hidden" id="ID_FOURNISSEUR" name="ID_FOURNISSEUR"  value="<?=$data['ID_FOURNISSEUR']?>">
                <div class="col-sm-6 form-group">
                  <label>Nom <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="NOM" name="NOM" placeholder="Nom" value="<?=set_value('NOM',$data['NOM'])?>">
                  <?php echo form_error('NOM', '<div class="text-danger">', '</div>'); ?>
                </div>
                  <div class="col-sm-6 form-group">
                  <label>E-mail <span class="text-danger">*</span></label>
                  <input class="form-control" type="email" class="form-control" id="EMAIL" name="EMAIL" placeholder="username@superbat.bi" value="<?=set_value('EMAIL',$data['EMAIL'])?>">
                  <?php echo form_error('EMAIL', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>T&eacute;l&eacute;phone <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="TELEPHONE" name="TELEPHONE" placeholder="T&eacute;l&eacute;phone" value="<?=set_value('TELEPHONE',$data['TELEPHONE'])?>">
                  <?php echo form_error('TELEPHONE', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-sm-6 form-group">
                  <label>Localit&eacute; <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="LOCALITE" name="LOCALITE" placeholder="Localit&eacute;" value="<?=set_value('LOCALITE',$data['LOCALITE'])?>">
                  <?php echo form_error('LOCALITE', '<div class="text-danger">', '</div>'); ?>
                </div>
              
    

           </div>

           <div class="form-group">
            <button class="btn btn-success btn-block" type="submit">Submit</button>
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
</body>
</html>
