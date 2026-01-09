<!DOCTYPE html>
<html lang="en">
<?php
  include VIEWPATH.'includes/header.php';
?>
<style>
  /* Taille uniforme du slider */
  #heroCarousel {
    width: 100%;
  }

  #heroCarousel .carousel-item {
    height: 600px; /* ajuste selon ton besoin */
  }

/* Images du slider */
#heroCarousel .carousel-item img {
  width: 100%;
  height: 100%;
  object-fit: cover; /* remplit sans déformer */
  object-position: center;
}

/* Amélioration du texte */
#heroCarousel .carousel-caption {
  background: rgba(0, 0, 0, 0.45);
  padding: 20px;
  border-radius: 10px;
}

/* Responsive mobile */
@media (max-width: 768px) {
  #heroCarousel .carousel-item {
    height: 250px;
  }
}

</style>
<body class="fixed-navbar">
  <div class="page-wrapper">
    <!-- START HEADER-->
    <?php
      include VIEWPATH.'includes/navbar.php';
    ?>
    <!-- END HEADER-->
    <!-- START SIDEBAR-->
    <?php
      include VIEWPATH.'includes/sidebarMenu.php';
    ?>
    <!-- END SIDEBAR-->


    <?php 
      if(!empty($this->session->flashdata('message')))
      echo $this->session->flashdata('message');
    ?>
    <div class="content-wrapper">
      <!-- START PAGE CONTENT-->
      <div class="page-content fade-in-up">
       <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="ibox bg-success color-white widget-stat">
            <div class="ibox-body">
              <h2 class="m-b-5 font-strong">201</h2>
              <div class="m-b-5">Matieres premieres</div><i class="ti-shopping-cart widget-stat-icon"></i>
              <div><i class="fa fa-level-up m-r-5"></i><small>25% higher</small></div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="ibox bg-info color-white widget-stat">
            <div class="ibox-body">
              <h2 class="m-b-5 font-strong">1250</h2>
              <div class="m-b-5">Production</div><i class="ti-bar-chart widget-stat-icon"></i>
              <div><i class="fa fa-level-up m-r-5"></i><small>17% higher</small></div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="ibox bg-warning color-white widget-stat">
            <div class="ibox-body">
              <h2 class="m-b-5 font-strong">$1570</h2>
              <div class="m-b-5">Produits finis</div><i class="fa fa-money widget-stat-icon"></i>
              <div><i class="fa fa-level-up m-r-5"></i><small>22% higher</small></div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="ibox bg-danger color-white widget-stat">
            <div class="ibox-body">
              <h2 class="m-b-5 font-strong">108</h2>
              <div class="m-b-5">Vente</div><i class="ti-user widget-stat-icon"></i>
              <div><i class="fa fa-level-down m-r-5"></i><small>-12% Lower</small></div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body p-0">

          <!-- Slider -->
          <div id="heroCarousel" class="carousel slide" data-ride="carousel" data-interval="5000">
            <div class="carousel-inner">

              <!-- Slide 1 -->
              <div class="carousel-item active">
                <img src="<?= base_url('assets/uploads/superbat.png'); ?>" class="d-block w-100" alt="Image 1">
                <div class="carousel-caption d-none d-md-block">
                  <h1 class="fw-bold"></h1>
                  <p></p>
                  <a href="<?= base_url('production/Production'); ?>" class="btn btn-primary btn-lg shadow">
                    production
                  </a>
                </div>
              </div>

              <!-- Slide 2 -->
              <div class="carousel-item">
                <img src="<?= base_url('assets/uploads/photo_s.jpeg'); ?>" class="d-block w-100" alt="Image 2">
                <div class="carousel-caption d-none d-md-block">
                  <h1 class="fw-bold"></h1>
                  <p></p>
                  <a href="<?= base_url('don'); ?>" class="btn btn-primary btn-lg shadow">
                    
                  </a>
                </div>
              </div>

              <!-- Slide 3 -->
              <div class="carousel-item">
                <img src="<?= base_url('assets/uploads/imagesps.jpeg'); ?>" class="d-block w-100" alt="Image 3">
                <div class="carousel-caption d-none d-md-block">
                  <h1 class="fw-bold"></h1>
                  <p></p>
                  <a href="<?= base_url('don'); ?>" class="btn btn-primary btn-lg shadow">
                    
                  </a>
                </div>
              </div>

              <!-- Slide 3 -->
              <div class="carousel-item">
                <img src="<?= base_url('assets/uploads/images_iok.jpeg'); ?>" class="d-block w-100" alt="Image 3">
                <div class="carousel-caption d-none d-md-block">
                  <h1 class="fw-bold"></h1>
                  <p></p>
                  <a href="<?= base_url('don'); ?>" class="btn btn-primary btn-lg shadow">
                    
                  </a>
                </div>
              </div>

            </div>

            <!-- Contrôles -->
            <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon"></span>
              <span class="sr-only">preview</span>
            </a>

            <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
              <span class="carousel-control-next-icon"></span>
              <span class="sr-only">Next</span>
            </a>

          </div>

        </div>
      </div>
    </div>
    <!-- END PAGE CONTENT-->
    <?php
      include VIEWPATH.'includes/footer.php';
    ?>
  </div>
</div>
<!-- BEGIN THEME CONFIG PANEL-->
<?php
  // include VIEWPATH.'includes/settings.php';
?>
<!-- END THEME CONFIG PANEL-->
<!-- BEGIN PAGA BACKDROPS-->
<div class="sidenav-backdrop backdrop"></div>
<div class="preloader-backdrop">
  <div class="page-preloader">Loading</div>
</div>
<!-- END PAGA BACKDROPS-->
<!-- CORE PLUGINS-->

<?php
  include VIEWPATH.'includes/scripts.php';
?>

</body>

</html>