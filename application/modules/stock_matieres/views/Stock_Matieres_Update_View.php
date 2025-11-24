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
            include 'includes/menu_stock.php';
          ?>
          <div class="ibox-body">
            <form id="FormData" action="<?php echo base_url()?>stock_matieres/Stock_matieres/update" method="POST" enctype="multipart/form-data">
              <div class="row">
                <input class="form-control" type="hidden" id="ID_STOCK_MATIERE" name="ID_STOCK_MATIERE"  value="<?=$data['ID_STOCK_MATIERE']?>">

                <div class="col-sm-6 form-group">
                  <label class="form-control-label">Type de matieres</label>
                  <select class="form-control select2_demo_1" id="ID_TYPE_MATIERE" name="ID_TYPE_MATIERE">
                    <option value="">--select--</option>
                    <?php foreach($type_matieres as $type){?>
                    <option value="<?= $type['ID_TYPE_MATIERE']?>" <?= set_value('ID_TYPE_MATIERE',$data['ID_TYPE_MATIERE']) == $type['ID_TYPE_MATIERE'] ? 'selected' : '' ?>><?= $type['DESCRIPTION'].'('.$type['CARACTERISTIQUE'].')' ?></option> 
                    <?php }?> 
                  </select>
                  <?php echo form_error('ID_TYPE_MATIERE', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>Numero de colis <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" class="form-control" id="NUMERO_COLIS" name="NUMERO_COLIS" placeholder="Numero de colis" value="<?=set_value('NUMERO_COLIS',$data['NUMERO_COLIS'])?>">
                  <?php echo form_error('NUMERO_COLIS', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>Longueur<span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="LONGEUR" name="LONGEUR" placeholder="Longueur" value="<?=set_value('LONGEUR',$data['LONGEUR'])?>">
                  <?php echo form_error('LONGEUR', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-sm-6 form-group">
                  <label>Couleur <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="COULEUR" name="COULEUR" placeholder="Couleur" value="<?=set_value('COULEUR',$data['COULEUR'])?>">
                  <?php echo form_error('COULEUR', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-sm-6 form-group">
                  <label>Quantité commandée <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="QUANTITE_COMMANDE" name="QUANTITE_COMMANDE" placeholder="Entrez la quantité" value="<?=set_value('QUANTITE_COMMANDE',$data['QUANTITE_COMMANDE'])?>">
                  <?php echo form_error('QUANTITE_COMMANDE', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>Quantité reçue <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" class="form-control" id="QUANTITE_RECUE" name="QUANTITE_RECUE" placeholder="Entrez la quantité" value="<?=set_value('QUANTITE_RECUE',$data['QUANTITE_RECUE'])?>">
                  <?php echo form_error('QUANTITE_RECUE', '<div class="text-danger">', '</div>'); ?>
                </div>
              

                <div class="col-sm-6 form-group">
                  <label class="form-control-label">Fournisseur <span class="text-danger">*</span></label>
                  <select class="form-control select2_demo_1" id="ID_FOURNISSEUR" name="ID_FOURNISSEUR">
                    <option value="">--select--</option>
                    <?php foreach($fournisseur as $type){?>
                    <option value="<?= $type['ID_FOURNISSEUR']?>" <?= set_value('ID_FOURNISSEUR',$data['ID_FOURNISSEUR']) == $type['ID_FOURNISSEUR'] ? 'selected' : '' ?>><?= $type['NOM'].'de '.$type['LOCALITE'].')' ?></option> 
                    <?php }?> 
                  </select>
                  <?php echo form_error('ID_FOURNISSEUR', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-sm-6 form-group">
                  <label>Date d'entrée <span class="text-danger">*</span></label>
                  <input class="form-control" type="date" id="DATE_ENTREE" name="DATE_ENTREE" value="<?=set_value('DATE_ENTREE',$data['DATE_ENTREE'])?>">
                  <?php echo form_error('DATE_ENTREE', '<div class="text-danger">', '</div>'); ?>
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
