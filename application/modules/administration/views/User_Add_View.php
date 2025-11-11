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
          include 'includes/menu_user.php';
          ?>
          <div class="ibox-body">
            <form id="FormData" action="<?php echo base_url()?>administration/User/add" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-sm-6 form-group">
                  <label>Nom <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="NOM" name="NOM" placeholder="Nom" value="<?=set_value('NOM')?>">
                  <?php echo form_error('NOM', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>Pr&eacute;nom <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="PRENOM" name="PRENOM" placeholder="Pr&eacute;nom" value="<?=set_value('PRENOM')?>">
                  <?php echo form_error('PRENOM', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>E-mail <span class="text-danger">*</span></label>
                  <input class="form-control" type="email" class="form-control" id="USERNAME" name="USERNAME" placeholder="username@superbat.bi" value="<?=set_value('USERNAME')?>">
                  <?php echo form_error('USERNAME', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>Password <span class="text-danger">*</span></label>
                  <div class="input-group-icon right">
                    <div class="input-icon">
                      <i class="fa fa-eye" id="togglePassword" style="cursor:pointer;"></i>
                    </div>
                    <input class="form-control" type="password" id="PASSWORD" name="PASSWORD" value="<?=set_value('PASSWORD')?>">
                  </div>
                  <?php echo form_error('PASSWORD', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="form-group col-lg-6">
                  <label for="PROFIL_ID">Profile <span class="text-danger">*</span> </label>
                  <select class="form-control" name="PROFIL_ID" id="PROFIL_ID">
                    <option>-- Select --</option>
                    <?php
                    foreach ($profil as $profils) {
                     echo"<option value='".$profils['PROFIL_ID']."'>".$profils['DESCRIPTION']."</option>";
                   }
                   ?>
                 </select>

                 <?php echo form_error('PROFIL_ID', '<div class="text-danger">', '</div>'); ?>
               </div> 
               <div class="form-group col-lg-6">
                <label for="ID_AGENCE">Agence <span class="text-danger">*</span> </label>
                <select class="form-control" name="ID_AGENCE" id="ID_AGENCE">
                  <option>-- Select --</option>
                  <?php
                  foreach ($agence as $agences) {
                   echo"<option value='".$agences['ID_AGENCE']."'>".$agences['DESCRIPTION']."</option>";
                 }
                 ?>
               </select>

               <?php echo form_error('ID_AGENCE', '<div class="text-danger">', '</div>'); ?>
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
<?php include VIEWPATH.'includes/settings.php'; ?>
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
