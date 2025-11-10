<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Superbat | Login</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="<?= base_url()?>/assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= base_url()?>/assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?= base_url()?>/assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="<?= base_url()?>/assets/css/main.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <link href="<?= base_url()?>/assets/css/pages/auth-light.css" rel="stylesheet" />
</head>

<body class="bg-silver-300">
    <div class="content">
        <div class="brand">
            <a class="link" >SUPERBAT</a>
        </div>
        <form id="login-fo" action="<?= base_url('Login/do_login') ?>" method="post">
            <h2 class="login-title">Log in</h2>

              <?php 
                          if(!empty($this->session->flashdata('message')))
                             echo $this->session->flashdata('message');
            ?>
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-envelope"></i></div>
                    <input class="form-control" type="USERNAME" name="USERNAME" placeholder="Email" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                    <input class="form-control" type="password" name="PASSWORD" id="password">
                </div>
            </div>
            <div class="form-group d-flex justify-content-between" onchange="myPassword()">
                <label class="ui-checkbox ui-checkbox-info">
                    <input type="checkbox">
                    <span class="input-span"></span>Voir le mot de passe</label>
                <a href="<?= base_url()?>/Login/forgotPassword">Mot de passe oublié?</a>
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-block" type="submit">Login</button>
            </div>
        
        </form>
    </div>
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS -->
    <script src="<?= base_url()?>/assets/vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <script src="<?= base_url()?>/assets/vendors/popper.js/dist/umd/popper.min.js" type="text/javascript"></script>
    <script src="<?= base_url()?>/assets/vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL PLUGINS -->
    <script src="<?= base_url()?>/assets/vendors/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
    <!-- CORE SCRIPTS-->
    <script src="<?= base_url()?>assets/js/app.js" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    <script type="text/javascript">
        $(function() {
            $('#login-form').validate({
                errorClass: "help-block",
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
            });
        });



function myPassword() {

  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
} 

    </script>
</body>

</html>