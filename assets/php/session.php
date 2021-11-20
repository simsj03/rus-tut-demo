<?php
	session_start();
	require_once 'auth.php';
	$cuser = new Auth();  

	if(!isset($_SESSION['user'])){
		header('location:index.php');
		die;
	}

	$currentUser = $_SESSION['user'];
	
	$data = $cuser->currentUser($currentUser);
	$cid = $data['id'];
	$cname = $data['name'];
	$cpassword = $data['password'];
	$cphone = $data['phone'];
	$cgender = $data['gender'];
	$cdob = $data['dob'];
	$cphoto = $data['photo'];
	$created = $data['created_at'];
	$verified = $data['verified'];

	$fname = strtok($cname," ");

	$reg_on = date('d M Y', strtotime($created));

	if($verified == 0){
		$verified = 'Not Verified!';
	}
	else{
		$verified = 'Verified!';
	}
	
?>