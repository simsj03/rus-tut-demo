<?php
	require_once 'admin-db.php';
	$admin = new Admin();
	session_start();

	//Handle admin login request
	if (isset($_POST['action']) && $_POST['action'] == 'adminLogin') {
	  $username = $admin->test_input($_POST['username']);
	  $password = $admin->test_input($_POST['password']);

	  $hpassword = sha1($password);

	  $loggedInAdmin = $admin->admin_login($username,$hpassword);

	  if ($loggedInAdmin != null) {
	    echo 'admin_login';
	    $_SESSION['username'] = $username;
	  } else {
	    echo $admin->showMessage('danger','Username or Password is Incorrect!');
	  }
	}  

	//Handle fetch all users request
	if (isset($_POST['action']) && $_POST['action'] == 'fetchAllUsers') {
	  $output = '';
	  $data = $admin->fetchAllUsers(0);
	  $path = '../assets/php/';

	  if ($data) {
	    $output .= '<table class="table table-striped table-bordered text-center">
									<thead>
										<tr>
											<th>#</th>
											<th>Image</th>
											<th>Name</th>
											<th>E-Mail</th>
											<th>Phone</th>
											<th>Gender</th>
											<th>Verified</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>';
	    foreach ($data as $row) {
	      if ($row['photo'] != '') {
	        $uphoto = $path . $row['photo'];
	      } else {
	        $uphoto = '../assets/img/avatar.png';
	      }
	      $output .= '<tr>
										<td>' . $row['id'] . '</td>
										<td><img src="' . $uphoto . '" class="rounded-circle" width="40px"></td>
										<td>' . $row['name'] . '</td>
										<td>' . $row['email'] . '</td>
										<td>' . $row['phone'] . '</td>
										<td>' . $row['gender'] . '</td>
										<td>' . $row['verified'] . '</td>
										<td>
											<a href="#" id="' . $row['id'] . '" title="View Details" class="text-success userDetailsIcon" data-toggle="modal" data-target="#showUserDetailsModal"><i class="fas fa-info-circle fa-lg"></i></a>&nbsp;&nbsp;

											<a href="#" id="' . $row['id'] . '" title="Delete User" class="text-danger userDeleteIcon"><i class="fas fa-trash fa-lg"></i></a>&nbsp;&nbsp;

											<a href="#" id="' . $row['id'] . '" title="Send URL" class="text-primary sendUrlIcon" data-toggle="modal" data-target="#showSendUrlModal"><i class="fas fa-link fa-lg"></i></a>
										</td>
									</tr>';
	    }
	    $output .= '</tbody>
								</table>';
	    echo $output;
	  } else {
	    echo '<h3 class="text-center text-secondary">:( No registered users</h3>';
	  }
	}

	//Handle display user's details request
	if (isset($_POST['details_id'])) {
	  $id = $_POST['details_id'];
	  $data = $admin->fetchUserDetailsByID($id);
	  echo json_encode($data);
	}

	//Handle delete a user request
	if (isset($_POST['del_id'])) {
	  $id = $_POST['del_id'];

	  $admin->userAction($id,0);
	}

	//Handle fetch all deleted users request
	if (isset($_POST['action']) && $_POST['action'] == 'fetchDeletedUsers') {
	  $output = '';
	  $data = $admin->fetchAllUsers(1);
	  $path = '../assets/php/';

	  if ($data) {
	    $output .= '<table class="table table-striped table-bordered text-center">
									<thead>
										<tr>
											<th>#</th>
											<th>Image</th>
											<th>Name</th>
											<th>E-Mail</th>
											<th>Phone</th>
											<th>Gender</th>
											<th>Verified</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>';
	    foreach ($data as $row) {
	      if ($row['photo'] != '') {
	        $uphoto = $path . $row['photo'];
	      } else {
	        $uphoto = '../assets/img/avatar.png';
	      }
	      $output .= '<tr>
										<td>' . $row['id'] . '</td>
										<td><img src="' . $uphoto . '" class="rounded-circle" width="40px"></td>
										<td>' . $row['name'] . '</td>
										<td>' . $row['email'] . '</td>
										<td>' . $row['phone'] . '</td>
										<td>' . $row['gender'] . '</td>
										<td>' . $row['verified'] . '</td>
										<td>
											<a href="#" id="' . $row['id'] . '" title="Restore User" class="text-white userRestoreIcon badge badge-dark p-2">Restore</a>
										</td>
									</tr>';
	    }
	    $output .= '</tbody>
								</table>';
	    echo $output;
	  } else {
	    echo '<h3 class="text-center text-secondary">:( No deleted users</h3>';
	  }
	}

	//handle restore deleted user request
	if (isset($_POST['res_id'])) {
	  $id = $_POST['res_id'];

	  $admin->userAction($id,1);
	}

	//handle export all users in excel
	// if (isset($_GET['export']) && $_GET['export'] == 'excel') {
	//   header('Content-Type: application/xls');    
	//   header('Content-Disposition: attachment; filename=users.xls');  
	//   header('Pragma: no-cache'); 
	//   header('Expires: 0');

	//   $data = $admin->exportAllUsers();
	//   echo '<table border="1" align=center>';
	//   echo '<tr>
	// 					<th>#</th>
	// 					<th>Name</th>
	// 					<th>E-Mail</th>
	// 					<th>Phone</th>
	// 					<th>DOB</th>
	// 					<th>Joined Date</th>
	// 					<th>Verified</th>
	// 					<th>Deleted</th>
	// 				</tr>';
	//   foreach ($data as $row) {
	//     echo '<tr>
	// 						<td>' . $row['id'] . '</td>
	// 						<td>' . $row['name'] . '</td>
	// 						<td>' . $row['email'] . '</td>
	// 						<td>' . $row['phone'] . '</td>
	// 						<td>' . $row['dob'] . '</td>
	// 						<td>' . $row['created_at'] . '</td>
	// 						<td>' . $row['verified'] . '</td>
	// 						<td>' . $row['deleted'] . '</td>
	// 				</tr>';
	//   }
	//   echo '</table>';
	// }

	//Fetch all notes of users
	if (isset($_POST['action']) && $_POST['action'] == 'fetchAllNotes') {
	  $note = $admin->fetchAllNotes();
	  $output = '';
	  if ($note) {
	    $output .= '<table class="table table-striped table-bordered text-center">
									<thead>
										<tr>
											<th>#</th>
											<th>User Name</th>
											<th>User E-Mail</th>
											<th>Note Title</th>
											<th>Note</th>
											<th>Written On</th>
											<th>Updated On</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>';
	    foreach ($note as $row) {
	      $output .= '<tr>
										<td>' . $row['id'] . '</td>
										<td>' . $row['name'] . '</td>
										<td>' . $row['email'] . '</td>
										<td>' . $row['title'] . '</td>
										<td>' . $row['note'] . '</td>
										<td>' . $row['created_at'] . '</td>
										<td>' . $row['updated_at'] . '</td>
										<td>
											<a href="#" id="' . $row['id'] . '" title="Delete Note" class="text-danger noteDeleteIcon"><i class="fas fa-trash fa-lg"></i></a>
										</td>
									</tr>';
	    }
	    $output .= '</tbody>
								</table>';
	    echo $output;
	  } else {
	    echo '<h3 class="text-center text-secondary">:( No new notes</h3>';
	  }
	}

	//handle delate note of an user by admin
	if (isset($_POST['note_id'])) {
	  $id = $_POST['note_id'];

	  $admin->deleteNoteOfUser($id);
	}

	// handle fetch all notification request
	if (isset($_POST['action']) && $_POST['action'] == 'fetchAllNotification') {
	  $notification = $admin->fetchNotification();

	  $output = '';
	  if ($notification) {
	    foreach ($notification as $row) {
	      $output .= '<div class="alert alert-dark" role="alert">
										 <button type="button" id="' . $row['id'] . '" class="close" data-dismiss="alert" aria-label="Close">
										   <span aria-hidden="true">&times;</span>
										 </button>
									  <h4 class="alert-heading text-danger">New Notification!</h4>
									  <p class="mb-0 lead">' . $row['message'] . ' by ' . $row['name'] . '</p>
									  <hr class="my-2">
								  	<p class="mb-0 float-left"><b>User E-Mail :</b> ' . $row['email'] . '</p>
								  	<p class="mb-0 float-right">' . $admin->timeAgo($row['created_at']) . '</p>
								  	<div class="clearfix"></div>
									</div>';
	    }
	    echo $output;
	  } else {
	    echo '<h3 class="text-center text-secondary mt-5">No new notifications</h3>';
	  }
	}

	//handle remove notification
	if (isset($_POST['notification_id'])) {
	  $id = $_POST['notification_id'];

	  $admin->removeNotification($id);
	}

	//Check notification
	if (isset($_POST['action']) && $_POST['action'] == 'notificationCheck') {
	  if ($admin->fetchNotification()) {
	    echo '<i class="fas fa-circle text-danger fa-sm"></i>';
	  } else {
	    echo '';
	  }
	}

	// Handle fetch all feedback
	if (isset($_POST['action']) && $_POST['action'] == 'fetchAllFeedback') {
	  $feedback = $admin->fetchFeedback();
	  $output = '';
	  if ($feedback) {
	    $output .= '<table class="table table-striped table-bordered text-center">
									<thead>
										<tr>
											<th>FID</th>
											<th>UID</th>
											<th>User Name</th>
											<th>User E-Mail</th>
											<th>Subject</th>
											<th>Feedback</th>
											<th>Sent On</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>';
	    foreach ($feedback as $row) {
	      $output .= '<tr>
										<td>' . $row['id'] . '</td>
										<td>' . $row['uid'] . '</td>
										<td>' . $row['name'] . '</td>
										<td>' . $row['email'] . '</td>
										<td>' . $row['subject'] . '</td>
										<td>' . $row['feedback'] . '</td>
										<td>' . $row['created_at'] . '</td>
										<td>
											<a href="#" fid="' . $row['id'] . '" id="' . $row['uid'] . '" title="Reply" class="text-primary feedbackReplyIcon" data-toggle="modal" data-target="#showReplyModal"><i class="fas fa-reply fa-lg"></i></a>
										</td>
									</tr>';
	    }
	    $output .= '</tbody>
								</table>';
	    echo $output;
	  } else {
	    echo '<h3 class="text-center text-secondary">:( No new feedback</h3>';
	  }
	}

	//Handle reply feedback to user request
	if (isset($_POST['message'])) {
	  $uid = $_POST['uid'];
	  $message = $_POST['message'];
	  $fid = $_POST['fid'];

	  $admin->replyFeedback($uid,$message);
	  $admin->feedbackReplied($fid);
	}

	// Handle insert new public url
	if (isset($_POST['sendUrl'])) {
	  $urls = explode(',', $_POST['url']);
	  foreach ($urls as $url) {
	    $admin->insertUrl(NULL, $url, 'public');
	  }
	}
	// Handle fetch all public urls
	if (isset($_POST['fetchAllPublicUrl'])) {
	  $urls = $admin->fetchAllPublicUrl();
	  $output = '';
	  if ($urls) {
	    $output .= '<table class="table table-striped table-bordered text-center table-sm">
									<thead>
										<tr>
											<th>ID</th>
											<th>URL</th>
											<th>Sent On</th>
											<th>Updated On</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>';
	    foreach ($urls as $row) {
	      if ($row['status'] == 'public') {
	        $status = '<a href="#" class="badge badge-success badge-pill">Public</a>';
	      } else {
	        $status = '<a href="#" class="badge badge-danger badge-pill">Private</a>';
	      }
	      $output .= '<tr>
										<td>' . $row['id'] . '</td>
										<td><a target="_blank" href="' . $row['url'] . '">' . $row['url'] . '</a></td>
										<td>' . $row['created_at'] . '</td>
										<td>' . $row['updated_at'] . '</td>
										<td>' . $status . '</td>
										<td>
											<a href="#" class="text-primary urlEditIcon" title="Edit" data-toggle="modal" data-target="#urlEditModal" id="' . $row['id'] . '"><i class="far fa-edit fa-lg"></i></a>&nbsp;

											<a href="#" id="' . $row['id'] . '" title="Delete URL" class="text-danger urlDeleteIcon"><i class="fas fa-trash fa-lg"></i></a>
										</td>
									</tr>';
	    }
	    $output .= '</tbody>
								</table>';
	    echo $output;
	  } else {
	    echo '<h3 class="text-center text-secondary">:( No URL sent</h3>';
	  }
	}

	// Handle edit an url ajax request
	if (isset($_POST['edit_url_id'])) {
	  $id = $_POST['edit_url_id'];
	  $data = $admin->fetchUrlById($id);
	  echo json_encode($data);
	}

	// Handle edit an url ajax request
	if (isset($_POST['editUrl'])) {
	  $edit_url_text = $_POST['editUrl'];
	  $edit_id_text = $_POST['editId'];
	  $admin->updatePublicUrl($edit_id_text, $edit_url_text);
	}

	// Handle delete an url ajax request
	if (isset($_POST['del_url_id'])) {
	  $id = $_POST['del_url_id'];
	  $admin->deleteAnUrl($id);
	}

	// Handle delete all public urls ajax request
	if (isset($_POST['delete_all_urls'])) {
	  $admin->deleteAllUrls('public');
	}

	// Handle delete all private urls ajax request
	if (isset($_POST['delete_all_private_urls'])) {
	  $admin->deleteAllUrls('private');
	}

	// Handle insert private url
	if (isset($_POST['insert_private_url'])) {
	  $user_id = $_POST['user_id'];
	  $urls = explode(',', $_POST['url_text']);
	  foreach ($urls as $url) {
	    $admin->insertUrl($user_id, $url, 'private');
	  }
	}

	// Handle fetch all deleted urls by users ajax request
	if (isset($_POST['fetchAllDeletedUrls'])) {
	  $urls = $admin->fetchAllDeletedUrls();
	  $output = '';
	  if ($urls) {
	    $output .= '<table class="table table-striped table-bordered text-center table-sm">
									<thead>
										<tr>
											<th>User ID</th>
											<th>User Name</th>
											<th>User Email</th>
											<th>URL</th>
											<th>Deleted On</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>';
	    foreach ($urls as $row) {
	      $output .= '<tr>
										<td>' . $row['uid'] . '</td>
										<td>' . $row['name'] . '</td>
										<td>' . $row['email'] . '</td>
										<td><a href="' . $row['url'] . '" target="_blank">' . $row['url'] . '</a></td>
										<td>' . $row['deleted_at'] . '</td>
										<td>
											<a href="#" id="' . $row['id'] . '" title="Delete URL" class="text-danger urlDeleteIcon"><i class="fas fa-trash fa-lg"></i></a>
										</td>
									</tr>';
	    }
	    $output .= '</tbody>
								</table>';
	    echo $output;
	  } else {
	    echo '<h3 class="text-center text-secondary">:( No deleted URL</h3>';
	  }
	}

	// Handle deleted urls delete ajax request
	if (isset($_POST['del_deleted_url_id'])) {
	  $id = $_POST['del_deleted_url_id'];
	  $admin->deleteADeletedUrl($id);
	}

	// Handle delete all deleted urls
	if (isset($_POST['deleteAllDeletedUrls'])) {
	  $admin->deleteAllDeletedUrls();
	}
?>
