<?php
	session_start();

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'vendor/phpmailer/phpmailer/src/Exception.php';
	require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
	require 'vendor/phpmailer/phpmailer/src/SMTP.php';
	
	require 'vendor/autoload.php';
	$mail = new PHPMailer(true);

	require_once 'auth.php';
	$user = new Auth();

	// Handle Register Request
	if (isset($_POST['action']) && $_POST['action'] == 'register') {
	  $name = $user->test_input($_POST['name']);
	  $email = $user->test_input($_POST['email']);
	  $pass = $user->test_input($_POST['password']);

	  $hpass = password_hash($pass, PASSWORD_DEFAULT);
	  if ($user->user_exist($email)) {
	    echo $user->showMessage('warning','This e-mail is already registered!');
	  } else {
	    if ($user->register($name,$email,$hpass)) {
	      echo 'register';
	      $_SESSION['user'] = $email;
	    } else {
	      echo $user->showMessage('danger','Something went wrong try again later!');
	    }
	  }
	}

	// Handle Login Request
	if (isset($_POST['action']) && $_POST['action'] == 'login') {
	  $email = $user->test_input($_POST['email']);
	  $pass = $user->test_input($_POST['password']);

	  $loggedUser = $user->login($email);
	  if ($loggedUser != null) {
	    if (password_verify($pass, $loggedUser['password'])) {
	      if (!empty($_POST['rem'])) {
	        setcookie('email',$email,time() + (10 * 365 * 24 * 60 * 60),'/');
	        setcookie('password',$pass,time() + (10 * 365 * 24 * 60 * 60),'/');
	      } else {
	        setcookie('email','',1,'/');
	        setcookie('password','',1,'/');
	      }

	      echo 'login';
	      $_SESSION['user'] = $email;
	    } else {
	      echo $user->showMessage('danger','Password is incorrect!');
	    }
	  } else {
	    echo $user->showMessage('danger','User not found!');
	  }
	}

	// Handle forgot password request
	if (isset($_POST['action']) && $_POST['action'] == 'forgot') {
	  $email = $user->test_input($_POST['email']);

	  $user_found = $user->currentUser($email);

	  if ($user_found != null) {
	    $token = uniqid();
	    $token = str_shuffle($token);
	    $user->forgot_password($token,$email);

	    try {
	      $mail->isSMTP();
	      $mail->Host = 'smtp.gmail.com';                    
	      $mail->SMTPAuth = true;                                  
	      $mail->Username = Database::USERNAME;                    
	      $mail->Password = Database::PASSWORD;                              
	      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
	      $mail->Port = 587;                                    

	      $mail->setFrom(Database::USERNAME, 'RussellE');
	      $mail->addAddress($email);     

	      $mail->isHTML(true);                    
	      $mail->Subject = 'Reset Password';
	      $mail->Body = '<h3>Click the below link to reset your password.</h3><br><a 
		  href="http://localhost/RussellE-demo/reset-pass.php?email=' . $email . '
		  &token=' . $token . '">http://localhost/Russelle-demo/reset-pass.php?email=' 
		  . $email . '&token=' . $token . '</a><br><h3>Regards<br>RussellE</h3>';

	      $mail->send();
	      echo $user->showMessage('success','We have send you the reset link in your email ID, please check your email!');
	    } catch (Exception $e) {
	      echo $user->showMessage('danger','Something went wrong please try again later!');
	    }
	  } else {
	    echo $user->showMessage('info','This e-mail is not registered!');
	  }
	}

	if (isset($_POST['action']) && $_POST['action'] == 'checkUser') {
	  if (!$user->currentUser($_SESSION['user'])) {
	    echo 'bye';
	    unset($_SESSION['user']);
	  }
	}
?>
