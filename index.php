<?php
  session_start();
  include_once 'config/url.php';
  include_once 'config/config.php';
  $db = new Database();

  $sql = 'UPDATE visitors SET hits = hits+1 WHERE id = 1';
  $stmt = $db->conn->prepare($sql);
  $stmt->execute();

  if (isset($_SESSION['user'])) {
    header('location:home.php');
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Russell-E Demo </title>
  <link rel="stylesheet" href="<?= $assets_url ?>/lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= $assets_url ?>/css/style.css">
  <link rel="stylesheet" href="<?= $assets_url ?>/lib/fontawesome/css/all.min.css">
</head>

<body class="bg-info">
  <div class="container">
    <!-- Login Box Start -->
    <div class="row justify-content-center wrapper" id="login-box">
      <div class="col-lg-10 my-auto">
        <div class="card-group myShadow">
          <div class="card rounded-left p-4" style="flex-grow:1.4;">
            <h1 class="text-center font-weight-bold text-primary">Russell Elementary Portal:    Sign in to Account</h1>
            <hr class="my-3">
            <form action="#" method="post" class="px-3" id="login-form">
              <div id="loginAlert"></div>
              <div class="input-group input-group-lg form-group">
                <div class="input-group-prepend">
                  <span class="input-group-text rounded-0"><i class="far fa-envelope fa-lg"></i></span>
                </div>
                <input type="email" id="email" name="email" class="form-control rounded-0" placeholder="E-Mail" required value="<?php if (isset($_COOKIE['email'])) {
  echo $_COOKIE['email'];
} ?>">
              </div>
              <div class="input-group input-group-lg form-group">
                <div class="input-group-prepend">
                  <span class="input-group-text rounded-0"><i class="fas fa-key fa-lg"></i></span>
                </div>
                <input type="password" id="password" name="password" class="form-control rounded-0" minlength="5" placeholder="Password" required value="<?php if (isset($_COOKIE['password'])) {
  echo $_COOKIE['password'];
} ?>">
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox float-left">
                  <input type="checkbox" class="custom-control-input" id="customCheck" name="rem" <?php if (isset($_COOKIE['email'])) { ?> checked <?php } ?>>
                  <label class="custom-control-label" for="customCheck">Remember me</label>
                </div>
                <div class="forgot float-right">
                  <a href="#" id="forgot-link">Forgot Password?</a>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="form-group">
                <input type="submit" id="login-btn" value="Sign In" class="btn btn-primary btn-lg btn-block myBtn">
              </div>
            </form>
          </div>
          <div class="card justify-content-center rounded-right myColor p-4">
            <h1 class="text-center font-weight-bold text-white">Hello Teacher</h1>
            <hr class="my-3 bg-light myHr">
            <p class="text-center font-weight-bolder text-light lead">Please enter your personal details to login.</p>
            <button class="btn btn-outline-light btn-lg align-self-center font-weight-bolder mt-4 myLinkBtn" id="register-link">Sign Up</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Login Box End -->

    <!-- Register Box Start -->
    <div class="row justify-content-center wrapper" style="display:none;" id="register-box">
      <div class="col-lg-10 my-auto">
        <div class="card-group myShadow">
          <div class="card rounded-left myColor p-4 justify-content-center">
            <h1 class="text-center font-weight-bold text-white">Welcome Back!</h1>
            <hr class="my-4 bg-light myHr">
            <p class="text-center font-weight-bolder text-light lead">Please enter your personal info and sign into system.</p>
            <button class="btn btn-outline-light btn-lg font-weight-bolder mt-4 align-self-center myLinkBtn" id="login-link">Sign In</button>
          </div>
          <div class="card rounded-right p-4" style="flex-grow:1.4">
            <h1 class="text-center font-weight-bold text-primary">Create Account</h1>
            <hr class="my-3">

            <form action="#" method="post" class="px-3" id="register-form">
              <div id="regAlert"></div>
              <div class="input-group input-group-lg form-group">
                <div class="input-group-prepend">
                  <span class="input-group-text rounded-0"><i class="far fa-user fa-lg"></i></span>
                </div>
                <input type="text" id="name" name="name" class="form-control rounded-0" placeholder="Full Name" required>
              </div>
              <div class="input-group input-group-lg form-group">
                <div class="input-group-prepend">
                  <span class="input-group-text rounded-0"><i class="far fa-envelope fa-lg"></i></span>
                </div>
                <input type="email" id="remail" name="email" class="form-control rounded-0" placeholder="E-Mail" required>
              </div>
              <div class="input-group input-group-lg form-group">
                <div class="input-group-prepend">
                  <span class="input-group-text rounded-0"><i class="fas fa-key fa-lg"></i></span>
                </div>
                <input type="password" id="rpassword" name="password" class="form-control rounded-0" minlength="5" placeholder="Password" required>
              </div>
              <div class="input-group input-group-lg form-group">
                <div class="input-group-prepend">
                  <span class="input-group-text rounded-0"><i class="fas fa-key fa-lg"></i></span>
                </div>
                <input type="password" id="cpassword" name="cpassword" class="form-control rounded-0" minlength="5" placeholder="Confirm Password" required>
              </div>
              <div class="form-group">
                <div id="passError" class="text-danger font-weight-bolder"></div>
              </div>
              <div class="form-group">
                <input type="submit" id="register-btn" value="Sign Up" class="btn btn-primary btn-lg btn-block myBtn">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Register Box End  -->

    <!-- Forgot Box Start -->
    <div class="row justify-content-center wrapper" id="forgot-box" style="display: none;">
      <div class="col-lg-10 my-auto">
        <div class="card-group myShadow">
          <div class="card rounded-left myColor p-4 justify-content-center">
            <h1 class="text-center font-weight-bold text-white">Reset Password!</h1>
            <hr class="my-4 bg-light myHr">
            <button class="btn btn-outline-light btn-lg font-weight-bolder myLinkBtn align-self-center" id="back-link">Back</button>
          </div>
          <div class="col-lg-7 bg-white rounded-right p-4">
            <h1 class="text-center font-weight-bold text-primary">Forgot Your Password?</h1>
            <hr class="my-3">
            <p class="lead text-center text-secondary">To reset your password, enter the registered e-mail adddress and we will send you password reset instructions on your e-mail!</p>
            <form action="#" method="post" class="px-3" id="forgot-form">
              <div id="forgotAlert"></div>
              <div class="input-group input-group-lg form-group">
                <div class="input-group-prepend">
                  <span class="input-group-text rounded-0"><i class="far fa-envelope fa-lg"></i></span>
                </div>
                <input type="email" id="femail" name="email" class="form-control rounded-0" placeholder="E-Mail" required>
              </div>
              <div class="form-group">
                <input type="submit" id="forgot-btn" value="Reset Password" class="btn btn-primary btn-lg btn-block myBtn">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Forgot Box End -->
  </div>
  <script src="<?= $assets_url ?>/lib/jquery-3.4.1.min.js"></script>
  <script src="<?= $assets_url ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
  $(document).ready(function() {
    $("#register-link").click(function() {
      $("#login-box").hide();
      $("#register-box").show();
    });
    $("#login-link").click(function() {
      $("#login-box").show();
      $("#register-box").hide();
    });
    $("#forgot-link").click(function() {
      $("#login-box").hide();
      $("#forgot-box").show();
    });
    $("#back-link").click(function() {
      $("#login-box").show();
      $("#forgot-box").hide();
    });

    //Register Ajax Request
    $("#register-btn").click(function(e) {
      if ($("#register-form")[0].checkValidity()) {
        e.preventDefault();
        $("#register-btn").val('Please Wait...');
        if ($("#rpassword").val() != $("#cpassword").val()) {
          $("#passError").text('* Password did not matched!');
          $("#register-btn").val('Sign Up');
        } else {
          $("#passError").text('');
          $.ajax({
            url: 'assets/php/action.php',
            method: 'post',
            data: $("#register-form").serialize() + '&action=register',
            success: function(response) {
              $("#register-btn").val('Sign Up');
              if (response === 'register') {
                window.location.replace('home.php');
              } else {
                $("#regAlert").html(response);
              }
            }
          });
        }
      }
    });

    // Login Ajax Request
    $("#login-btn").click(function(e) {
      if ($("#login-form")[0].checkValidity()) {
        e.preventDefault();
        $("#login-btn").val('Please Wait...');
        $.ajax({
          url: 'assets/php/action.php',
          method: 'post',
          data: $("#login-form").serialize() + '&action=login',
          success: function(response) {
            $("#login-btn").val('Sign In');
            if (response === 'login') {
              window.location = 'home.php';
            } else {
              $("#loginAlert").html(response);
            }
          }
        });
      }
    });

    //Forgot Password Ajax Request
    $("#forgot-btn").click(function(e) {
      if ($("#forgot-form")[0].checkValidity()) {
        e.preventDefault();
        $("#forgot-btn").val('Please Wait...');
        $.ajax({
          url: 'assets/php/action.php',
          method: 'post',
          data: $("#forgot-form").serialize() + '&action=forgot',
          success: function(response) {
            $("#forgot-btn").val('Reset Password');
            $("#forgot-form")[0].reset();
            $("#forgotAlert").html(response);
          }
        });
      }
    });

  });
  </script>
</body>

</html>
<?php include 'filesLogic.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="style.css">
    <title>Files Upload and Download</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <form action="index.php" method="post" enctype="multipart/form-data" >
          <h3>Upload File</h3>
          <input type="file" name="myfile"> <br>
          <button type="submit" name="save">upload</button>
        </form>
      </div>
    </div>
  </body>
</html>