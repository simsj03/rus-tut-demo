<?php
	require_once 'session.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'vendor/phpmailer/phpmailer/src/Exception.php';
	require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
	require 'vendor/phpmailer/phpmailer/src/SMTP.php';

	require 'vendor/autoload.php';
	$mail = new PHPMailer(true); 

	//Handle add new note
	if (isset($_POST['action']) && $_POST['action'] == 'add_note') {
	  $title = $cuser->test_input($_POST['title']);
	  $note = $cuser->test_input($_POST['note']);

	  $cuser->add_new_note($cid,$title,$note);
	  $cuser->notification($cid,'admin','New note added');
	}

	//Handle display all note of a user
	if (isset($_POST['action']) && $_POST['action'] == 'disp_all_notes') {
	  $output = '';
	  $notes = $cuser->get_notes($cid);

	  if ($notes) {
	    $output .= '<table class="table table-striped text-center">
          <thead>
          	<tr>
          		<th>#</th>
          		<th>Title</th>
          		<th>Note</th>
          		<th>Action</th>
          	</tr>
          </thead>
          <tbody>';
	    foreach ($notes as $row) {
	      $output .= '<tr>
	          		<td>' . $row['id'] . '</td>
	          		<td>' . $row['title'] . '</td>
	          		<td>' . substr($row['note'],0,75) . '...</td>
	          		<td>
	          			<a href="#" class="text-success infoBtn" title="View Details" id="' . $row['id'] . '"><i class="fas fa-info-circle fa-lg"></i></a>&nbsp;

	  							<a href="#" class="text-primary editBtn" title="Edit" data-toggle="modal" data-target="#editNoteModal" id="' . $row['id'] . '"><i class="far fa-edit fa-lg"></i></a>&nbsp;

	  							<a href="#" class="text-danger deleteBtn" title="Delete" id="' . $row['id'] . '"><i class="fas fa-trash-alt fa-lg"></i></a>
	          		</td>
          	</tr>';
	    }
	    $output .= '</tbody></table>';
	    echo $output;
	  } else {
	    echo '<h3 class="text-center text-secondary">Hello Teacher. Write your first note here.</h3>';
	  }
	}

	//handle delete note of a user
	if (isset($_POST['del_id'])) {
	  $id = $_POST['del_id'];
	  $cuser->delete_note($id);
	  $cuser->notification($cid,'admin','Note deleted');
	}

	//handle edit note of a user
	if (isset($_POST['edit_id'])) {
	  $id = $_POST['edit_id'];

	  $row = $cuser->edit_note($id);
	  echo json_encode($row);
	}

	//handle update note of a user
	if (isset($_POST['action']) && $_POST['action'] == 'update_note') {
	  $id = $_POST['id'];
	  $title = $cuser->test_input($_POST['title']);
	  $note = $cuser->test_input($_POST['note']);

	  $cuser->update_note($id,$title,$note);
	  $cuser->notification($cid,'admin','Note updated');
	}

	//handle display note in details
	if (isset($_POST['info_id'])) {
	  $id = $_POST['info_id'];

	  $row = $cuser->edit_note($id);
	  echo json_encode($row);
	}

	if (isset($_FILES['image'])) {
	  $name = $cuser->test_input($_POST['name']);
	//   $gender = $cuser->test_input($_POST['gender']);
	  $dob = $cuser->test_input($_POST['dob']);
	  $phone = $cuser->test_input($_POST['phone']);

	  $oldimage = $_POST['oldimage'];
	  $folder = 'uploads/';

	  if (isset($_FILES['image']['name']) && ($_FILES['image']['name'] != '')) {
	    $newimage = $folder . $_FILES['image']['name'];
	    move_uploaded_file($_FILES['image']['tmp_name'], $newimage);
	    if ($oldimage != null) {
	      unlink($oldimage);
	    }
	  } else {
	    $newimage = $oldimage;
	  }

	  $cuser->update_profile($name, $gender, $dob, $phone, $newimage, $cid);
	  $cuser->notification($cid,'admin','Profile updated');
	}

	//Handle change password request
	if (isset($_POST['action']) && $_POST['action'] == 'changepass') {
	  $curpass = $_POST['curpass'];
	  $newpass = $_POST['newpass'];
	  $cnewpass = $_POST['cnewpass'];

	  $hnewpass = password_hash($newpass, PASSWORD_DEFAULT);

	  if ($newpass != $cnewpass) {
	    echo $cuser->showMessage('danger','Password did not matched!');
	  } else {
	    if (password_verify($curpass, $cpassword)) {
	      $cuser->change_password($hnewpass,$cid);
	      echo $cuser->showMessage('success','Password Changed Successfully!');
	      $cuser->notification($cid,'admin','Password changed');
	    } else {
	      echo $cuser->showMessage('danger','Current Password is Wrong!');
	    }
	  }
	}

	//Send verify email link again
	if (isset($_POST['verify'])) {
	  $mail->isSMTP();
	  $mail->Host = 'smtp.gmail.com';                    
	  $mail->SMTPAuth = true;                                  
	  $mail->Username = Database::USERNAME;                    
	  $mail->Password = Database::PASSWORD;                              
	  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
	  $mail->Port = 587;                                    

	  $mail->setFrom(Database::USERNAME, 'RussellE');
	  $mail->addAddress($currentUser);     

	  $mail->isHTML(true);                    
	  $mail->Subject = 'E-Mail Verification';
	  $mail->Body = '<h3>Click the below link to verify your E-Mail.</h3><br><a href="http://localhost/RussellE-demo/verify-email.php?email=' . $currentUser . '">http://localhost/RussellE-demo/verify-email.php?email=' . $currentUser . '</a><br><h3>Regards<br>RussellE</h3>';

	  if ($mail->send()) {
	    echo $cuser->showMessage('success','Verification link has been sent to your E-mail.');
	  } else {
	    echo $cuser->showMessage('danger','Something Went Wrong!');
	  }
	}

	//handle send feedback request
	if (isset($_POST['action']) && $_POST['action'] == 'feedback') {
	  $subject = $cuser->test_input($_POST['subject']);
	  $feedback = $cuser->test_input($_POST['feedback']);

	  $cuser->send_feedback($subject,$feedback,$cid);
	  $cuser->notification($cid,'admin','Feedback sent');
	}

	//handle notification from admin
	if (isset($_POST['action']) && $_POST['action'] == 'fetchAllNotification') {
	  $notification = $cuser->fetchNotification($cid);

	  $output = '';
	  if ($notification) {
	    foreach ($notification as $row) {
	      $output .= '<div class="alert alert-danger" role="alert">
										 <button type="button" id="' . $row['id'] . '" class="close" data-dismiss="alert" aria-label="Close">
										   <span aria-hidden="true">&times;</span>
										 </button>
									  <h4 class="alert-heading">New Notification!</h4>
									  <p class="mb-0 lead">' . $row['message'] . '</p>
									  <hr class="my-2">
								  	<p class="mb-0 float-left">Reply of feedback from Admin</p>
								  	<p class="mb-0 float-right">' . $cuser->timeAgo($row['created_at']) . '</p>
								  	<div class="clearfix"></div>
									</div>';
	    }
	    echo $output;
	  } else {
	    echo '<h3 class="text-center text-secondary mt-5">No new notifications.</h3>';
	  }
	}

	//handle remove notification
	if (isset($_POST['notification_id'])) {
	  $id = $_POST['notification_id'];

	  $cuser->removeNotification($id);
	}

	//Check notification
	if (isset($_POST['action']) && $_POST['action'] == 'notificationCheck') {
	  if ($cuser->fetchNotification($cid)) {
	    echo '<i class="fas fa-circle text-danger fa-sm"></i>';
	  } else {
	    echo '';
	  }
	}

	// Handle fetch all public urls
	// if (isset($_POST['fetchAllPublicUrls'])) {
	//   $output = '';
	//   $urls = $cuser->fetchPublicUrls();

	//   if ($urls) {
	//     $output .= '<table kclass="table table-sm table-bordered table-striped text-center">
    //       <thead>
    //       	<tr>
    //       		<th>#</th>
    //       		<th>URL</th>
    //       		<th>Sent On</th>
    //       		<th>Action</th>
    //       	</tr>
    //       </thead>
    //       <tbody>';
	//     foreach ($urls as $row) {
	//       $output .= '<tr>
	//           		<td>' . $row['id'] . '</td>
	//           		<td><a href="' . $row['url'] . '" class="text-info" title="Open Link" target="_blank">' . $row['url'] . '</a></td>
	//           		<td>' . $row['created_at'] . '</td>
	//           		<td>
	// 								<a jkhref="' . $row['url'] . '" class="badge badge-pill badge-primary p-2" title="Open Link" target="_blank">Open</a>
	//           		</td>
    //       	</tr>';
	//     }
	//     $output .= '</tbody></table>';
	//     echo $output;
	//   } else {
	//     echo '<h3 class="text-center text-secondary">No public urls sent yet.</h3>';
	//   }
	// }

	// Handle fetch all private urls
	// if (isset($_POST['fetchAllPrivateUrls'])) {
	//   $uid = $_POST['uid'];
	//   $output = '';
	//   $urls = $cuser->fetchPrivateUrls($uid);

	//   if ($urls) {
	//     $output .= '<table class="table table-sm table-bordered table-striped text-center" id="private-urls">
    //       <thead>
    //       	<tr>
    //       		<th>#</th>
    //       		<th>URL</th>
    //       		<th>Sent On</th>
    //       		<th>Action</th>
    //       	</tr>
    //       </thead>
    //       <tbody>';
	//     foreach ($urls as $row) {
	//       $output .= '<tr>
	//           		<td>' . $row['id'] . '</td>
	//           		<td><a href="' . $row['url'] . '" class="text-info" title="Open Link" target="_blank">' . $row['url'] . '</a></td>
	//           		<td>' . $row['created_at'] . '</td>
	//           		<td>
	// 								<a href="' . $row['url'] . '" class="badge badge-pill badge-primary p-2" title="Open Link" target="_blank">Open</a>

	// 								<a href="#" id="' . $row['id'] . '" class="badge badge-pill badge-danger p-2 deleteUrlIcon" title="Delete Link">Delete</a>
	//           		</td>
    //       	</tr>';
	//     }
	//     $output .= '</tbody></table>';
	//     echo $output;
	//   } else {
	//     echo '<h3 dlass="text-center text-secondary"> You have not received any URLs yet.</h3>';
	//   }
	// }

	// Handle delete private url
	// if (isset($_POST['delete_url_id'])) {
	//   $url_id = $_POST['delete_url_id'];
	//   $cuser->deletesPrivateUrl($url_id);
	// }
?>
