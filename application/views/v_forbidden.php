<!doctype html>
<html lang="en">

<head>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Forbidden</title>

  <!-- Bootstrap CSS-->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/vendors/bootstrap/css/bootstrap.css">
  <!-- Style CSS (White)-->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/css/White.css">
  <!-- Style CSS (Dark)-->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/css/Dark.css">
  <!-- FontAwesome CSS-->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/vendors/fontawesome/css/all.css">

</head>

<body>

  <div class="lds-ring" id="loadAjax">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>

  <div class="auth-dark">
    <div class="theme-switch-wrapper">
      <label class="theme-switch" for="checkbox">
        <input type="checkbox" id="checkbox" title="Dark Or White" />
        <div class="slider round"></div>
      </label>
    </div>
  </div>

  <!--Errors Pages-->
  <div id="error">
    <div class="container text-center">
      <div class="pt-8">
        <h1 class="errors-titles">403</h1>
        <p>You Don't allowed to access this page</p>
        <a onclick="goBack()" class="text-white btn btn-primary">Back to page</a>
      </div>
    </div>
  </div>

  <!-- Library Javascipt-->
  <script src="<?= base_url(); ?>assets/vendor/vendors/bootstrap/js/jquery.min.js"></script>
  <script src="<?= base_url(); ?>assets/vendor/js/script.js"></script>
</body>

</html>