<?php
  require_once dirname(__DIR__, 2) . '/config/url.php';  
	require_once $root_dir . '/assets/php/session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?= ucfirst(basename($_SERVER['PHP_SELF'], '.php')); ?> | Russell Elementary Potal</title>
  <link rel="stylesheet" href="<?= $assets_url ?>/lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= $assets_url ?>/lib/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="<?= $assets_url ?>/lib/datatable/datatables.min.css" />
  <style type="text/css">
  @import url('https://fonts.googleapis.com/css?family=Maven+Pro:400,500,600,700,800,900&display=swap');

  * {
    font-family: 'Maven Pro', sans-serif;
    color:red
  }

  </style>
</head>

<body>
  <nav class="navbar navbar-expand-md bg-light navbar-light">
    <!-- Brand -->
    <a class="navbar-brand" href="home.php"><i class="fas fa-chalkboard"></i></i>&nbsp;&nbsp;Russell Elementary Portal</a>
    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>" href="<?= $base_url ?>/home.php"><i class="fas fa-home"></i>&nbsp;Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'urls.php') ? 'active' : ''; ?>" href="<?= $base_url ?>/urls.php"><i class="fas fa-link"></i>&nbsp;URL Section</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>" href="<?= $base_url ?>/profile.php"><i class="fas fa-user-circle"></i>&nbsp;Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'feedback.php') ? 'active' : ''; ?>" href="<?= $base_url ?>/feedback.php"><i class="fas fa-comment-dots"></i>&nbsp;Feedback</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'notification.php') ? 'active' : ''; ?>" href="<?= $base_url ?>/notification.php"><i class="fas fa-bell"></i>&nbsp;Notification&nbsp;<span id="showNotificationCheck"></span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'documents.php') ? 'active' : ''; ?>" href="<?= $base_url ?>/documents.php"><i class="fas fa-file-upload"></i>&nbsp;Documents&nbsp;</a>
        </li>
        
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
            <i class="fas fa-user-cog"></i>&nbsp;Hi! <?= $fname; ?>
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="#"><i class="fas fa-cog"></i>&nbsp;Setting</a>
            <a class="dropdown-item" href="<?= $base_url ?>/assets/php/logout.php"><i class="fas fa-sign-out-alt"></i>&nbsp;Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
