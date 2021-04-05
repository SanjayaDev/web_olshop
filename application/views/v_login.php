<!doctype html>
<html lang="en">

<head>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title><?= $title ?></title>

  <!-- Bootstrap CSS-->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/vendors/bootstrap/css/bootstrap.css">
  <!-- Style CSS (White)-->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/css/White.css">
  <!-- Style CSS (Dark)-->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/css/Dark.css">
  <!-- FontAwesome CSS-->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/vendors/fontawesome/css/all.css">
  <!-- Icon LineAwesome CSS-->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/vendors/lineawesome/css/line-awesome.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/vendors/sweetalert2/sweetalert2.min.css">

</head>

<body>

  <div class="auth-dark">
    <div class="theme-switch-wrapper">
      <label class="theme-switch" for="checkbox">
        <input type="checkbox" id="checkbox" title="Dark Or White" checked>
        <div class="slider round"></div>
      </label>
    </div>
  </div>
  <div class="container">
    <div class="row vh-100 d-flex justify-content-center align-items-center auth">
      <div class="col-md-7 col-lg-5">
        <div class="card">
          <div class="card-body">
            <h3 class="mb-5">LOGIN</h3>
            <?= form_open("validation_login") ?>
              <div class="form-group">
                <input type="email" name="email" class="form-control <?= form_error("email") != NULL ? "is-invalid" : FALSE; ?>" placeholder="Email">
                <?= form_error("email", "<small class='text-danger'>", "</small>"); ?>
              </div>
              <div class="form-group">
                <input type="password" name="password" class="form-control <?= form_error("password") != NULL ? "is-invalid" : FALSE; ?>" placeholder="Password">
                <?= form_error("password", "<small class='text-danger'>", "</small>"); ?>
              </div>
              <div class="row">
                <div class="col-8 d-block ml-auto">
                  <a href="forgot.html">Forgot your password?</a>
                </div>
              </div>
              <div class="form-group my-4">
                <input type="submit" class="btn btn-linear-primary btn-rounded px-5" value="Login">
              </div>
              <p>New member? <a href="signup.html" id="create_account">Create account</a></p>
            <?= form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Library Javascipt-->
  <script src="<?= base_url(); ?>assets/vendor/vendors/bootstrap/js/jquery.min.js"></script>
  <script src="<?= base_url(); ?>assets/vendor/vendors/sweetalert2/sweetalert2.min.js"></script>
  <script src="<?= base_url(); ?>assets/vendor/js/script.js"></script>
  <?= $this->session->flashdata("pesan"); ?>
  <script src="<?= base_url(); ?>assets/vendor/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
  <?php $this->session->sess_destroy(); ?>
</body>

</html>