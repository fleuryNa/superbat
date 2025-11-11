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
            include 'includes/menu_profil.php';
          ?>
          <div class="ibox-body">
            <form id="FormData" action="<?php echo base_url()?>administration/Profil_Droit/update" method="POST" enctype="multipart/form-data">
              
               <input class="form-control" type="hidden" id="PROFIL_ID" name="PROFIL_ID"  value="<?=$data['PROFIL_ID']?>">
              <div class="form-group">
                <label>Nom du Profil</label>
                <input class="form-control" type="text" id="DESCRIPTION" name="DESCRIPTION" placeholder="Nom Profil" value="<?=set_value('DESCRIPTION',$data['DESCRIPTION'])?>">
                 <?php echo form_error('DESCRIPTION', '<div class="text-danger">', '</div>'); ?>
              </div>
              <div class="form-group">
                <label>Les droits</label>
                <div class="row">
                  <?php
                    foreach ($droits as $value) {
                      $verif = $this->Model->getRequeteOne('SELECT * FROM `config_profil_droit` WHERE `PROFIL_ID` = '.$data['PROFIL_ID'].' AND `ID_DROIT` = '.$value['ID_DROIT'].'');
                      ?>
                  <div class="col-4 m-b-20">
                    <div class="check-list">
                      <label class="ui-checkbox">
                        <input type="checkbox" id="customCheckbox<?php echo $value['ID_DROIT']?>" <?php if (!empty($verif)) {  echo 'checked';}?> name="ID_DROIT[]" value="<?php echo $value['ID_DROIT']?>">
                        <span class="input-span"></span><?php echo $value['DESCRIPTION']?></label>
                      </div>
                    </div>
                    <?php
                    }
                      ?>
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


</body>
</html>
