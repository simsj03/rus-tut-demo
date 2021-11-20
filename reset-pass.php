<?php
	require_once 'assets/php/auth.php';
	$user = new Auth();
	$msg="";

	if(isset($_GET['email']) && isset($_GET['token'])){
		$email = $user->test_input($_GET['email']);
		$token = $user->test_input($_GET['token']);

		$auth_user = $user->reset_pass_auth($email,$token);
		if($auth_user != null){
			if(isset($_POST['submit'])){
				$newpass = $_POST['pass'];
				$cnewpass = $_POST['cpass'];

				$hnewpass = password_hash($newpass, PASSWORD_DEFAULT);
				if($newpass == $cnewpass){
					$user->update_new_pass($hnewpass,$email);
					$msg="Password changed successfully!<br><a href='index.php'>Login Here</a>";
				}
				else{
					$msg = "Password did not matched!";
				}
			}
		}
		else{
			header("location:index.php");
			exit();
		}
	}
	else{
		header("location:index.php");
		exit();
	} 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="author" content="RussellE">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<div class="container">
		<div class="row justify-content-center wrapper">
      <div class="col-lg-10 my-auto myShadow">
        <div class="row">
          <div class="col-lg-4 bg-primary rounded-left myColor p-4 text-center">
            <h1 class="text-center font-weight-bold text-white mt-5 pt-2">Reset Your Password Here!</h1>
          </div>
          <div class="col-lg-8 bg-white rounded-right p-4">
            <h1 class="text-center font-weight-bold text-primary">Enter New Password</h1>
            <hr class="my-3">
            <form action="#" method="post" class="px-3" id="reset-form">
              <div class="text-center lead mb-2"><?= $msg; ?></div>
              <div class="input-group input-group-lg form-group">
                <div class="input-group-prepend">
                  <span class="input-group-text rounded-0"><i class="fas fa-key fa-lg"></i></span>
                </div>
                <input type="password" id="pass" name="pass" class="form-control rounded-0" placeholder="New Password" required minlength="5">
              </div>
              <div class="input-group input-group-lg form-group">
                <div class="input-group-prepend">
                  <span class="input-group-text rounded-0"><i class="fas fa-key fa-lg"></i></span>
                </div>
                <input type="password" id="cpass" name="cpass" class="form-control rounded-0" placeholder="Confirm Password" required minlength="5">
              </div>
              <div class="form-group">
                <input type="submit" name="submit" id="reset-btn" value="Reset Password" class="btn btn-success btn-lg btn-block myBtn">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" defer></script>
  <html>
  <head>
    <title>Title of the document</title>
    <style>
      .button {
        background-color: #1c87c9;
        border: none;
        color: white;
        padding: 15px 25px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 12px;
        margin: 4px 2px;
        cursor: pointer;
        position: fixed;
    bottom: 10px;
    left: 50%;
    margin-left: -104.5px; /*104.5px is half of the button's width*/
      }
    </style>
  </head>
  <body>
    <a href="https://docs.google.com/document/d/1aaWDsmpl2Oi2U7GuGyhtiQ7WQYhG0hloqyk8lmdEEfs/edit?usp=sharing" class="button">Need Help?</a>
  </body>
</html>