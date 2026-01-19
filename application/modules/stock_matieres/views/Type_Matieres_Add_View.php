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
          include 'includes/menu_type_matieres.php';
          ?>
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
            <form id="FormData" action="<?php echo base_url()?>stock_matieres/Type_matieres/add" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-sm-12 form-group">
                  <label>Description <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="DESCRIPTION" name="DESCRIPTION" placeholder="Description" value="<?=set_value('DESCRIPTION')?>">
                  <?php echo form_error('DESCRIPTION', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>Unite de mesure  <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" class="form-control" id="UNITE" name="UNITE" placeholder="Unite de mesure " value="<?=set_value('UNITE')?>">
                  <?php echo form_error('UNITE', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-sm-6 form-group">
                  <label>Abbreviation </label>
                  <input class="form-control" type="text" class="form-control" id="TYPE_ABREV" name="TYPE_ABREV" placeholder="Abbreviation" value="<?=set_value('TYPE_ABREV')?>">
                  <?php echo form_error('TYPE_ABREV', '<div class="text-danger">', '</div>'); ?>
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


<script>
  $(document).ready(function () {
    setTimeout(function () {
      $('.alert').fadeOut('slow');
    }, 4000);
  });

</script>
</body>
</html>
