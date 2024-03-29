<?php  
	include_once 'assets/php/header.php';
?>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div id="showAlert"></div>
      <div class="card rounded-0 mt-3 border-danger">
        <div class="card-header border-daner">
          <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
              <a class="nav-link active font-weight-bold" data-toggle="tab" href="#profile">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" data-toggle="tab" href="#editprofile">Edit Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" data-toggle="tab" href="#changepass">Change Password</a>
            </li>
          </ul>
        </div>
        <style> 
        .nav-item{
            color:red;
        }
        </style>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane container active" id="profile">
              <div id="verifyEmailAlert"></div>
              <div class="card-deck">
                <div class="card border-danger">
                  <div class="card-header bg-danger text-light text-center lead">
                    User ID : <?= $cid; ?>
                  </div>
                  <div class="card-body">
                    <p class="card-text p-2 m-1 rounded" style="border:1px solid red;"><b>Name : </b><?= $cname; ?></p>
                    <p class="card-text p-2 m-1 rounded" style="border:1px solid red;"><b>E-Mail : </b><?= $currentUser; ?></p>
                    <p class="card-text p-2 m-1 rounded" style="border:1px solid red;"><b>Registered On : </b><?= $reg_on; ?></p>
                    <p class="card-text p-2 m-1 rounded" style="border:1px solid red;">
                      <b>E-Mail Verified : </b><?= $verified; ?>
                      <?php if ($verified == 'Not Verified!'): ?>
                      <a href="#" id="verify-email" class="float-right">Verify Now</a>
                      <?php endif; ?>
                    </p>
                    <div class="clearfix"></div>
                  </div>
                </div>
                <div class="card border-primary align-self-center">
                  <?php if (!$cphoto): ?>
                  <img src="assets/img/avatar.png" class="img-thumbnail img-fluid" width="408px">
                  <?php else: ?>
                  <img src="<?= 'assets/php/' . $cphoto; ?>" class="img-thumbnail img-fluid" width="408px">
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="tab-pane container fade" id="editprofile">
              <div class="card-deck">
                <div class="card border-danger align-self-center">
                  <?php if (!$cphoto): ?>
                  <img src="assets/img/avatar.png" class="img-thumbnail img-fluid" width="408px">
                  <?php else: ?>
                  <img src="<?= 'assets/php/' . $cphoto; ?>" class="img-thumbnail img-fluid" width="408px">
                  <?php endif; ?>
                </div>
                <div class="card border-danger">
                  <form action="" method="post" class="px-3 mt-2" enctype="multipart/form-data" id="profile-update-form">
                    <input type="hidden" name="oldimage" value="<?= $cphoto; ?>">
                    <div class="form-group m-0">
                      <label for="profilephoto" class="m-1">Upload Profile Image</label>
                      <input type="file" id="profilephoto" name="image">
                    </div>
                    <div class="form-group m-0">
                      <label for="name" class="m-1">Name</label>
                      <input type="text" name="name" id="name" class="form-control" value="<?= $cname; ?>">
                    </div>

                    <div class="form-group mt-2">
                      <input type="submit" name="profile_update" id="profileUpdateBtn" value="Update Profile" class="btn btn-danger btn-block">
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="tab-pane container fade" id="changepass">
              <div id="changepassAler"></div>
              <div class="card-deck">
                <div class="card border-success">
                  <div class="card-header bg-danger text-white text-center lead">Change Password</div>
                  <form action="#" method="post" class="px-3 mt-2" id="change-pass-form">
                    <div class="form-group">
                      <label for="curpass">Enter Your Current Password</label>
                      <input type="password" name="curpass" id="curpass" class="form-control form-control-lg" placeholder="Current Password" minlength="5" required>
                    </div>
                    <div class="form-group">
                      <label for="newpass">Enter New Password</label>
                      <input type="password" name="newpass" id="newpass" class="form-control form-control-lg" placeholder="New Password" minlength="5" required>
                    </div>
                    <div class="form-group">
                      <label for="cnewpass">Confirm New Password</label>
                      <input type="password" name="cnewpass" id="cnewpass" class="form-control form-control-lg" placeholder="Confirm New Password" minlength="5" required>
                      <div id="cnewpassError" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                      <input type="submit" name="changepass" value="Change Password" class="btn btn-success btn-block btn-lg" id="changePassBtn">
                    </div>
                  </form>
                </div>
                <div class="card border-success align-self-center">
                  <img src="assets/img/pass_change.jpg" class="img-thumbnail img-fluid" width="408px">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once 'assets/php/footer.php'; ?>
<script type="text/javascript">
$(document).ready(function() {

  // Checking user is logedin or not
  $.ajax({
    url: 'assets/php/action.php',
    method: 'post',
    data: {
      action: 'checkUser'
    },
    success: function(response) {
      if (response === 'bye') {
        window.location = 'index.php';
      }
    }
  });

  //Profile Update ajax request
  $("#profile-update-form").submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: 'assets/php/process.php',
      method: 'post',
      processData: false,
      contentType: false,
      cache: false,
      data: new FormData(this),
      success: function(response) {
        location.reload();
      }
    });
  });
  // Change Password ajax request

  $("#changePassBtn").click(function(e) {
    if ($("#change-pass-form")[0].checkValidity()) {
      e.preventDefault();
      $("#changePassBtn").val('Please Wait...');

      if ($("#newpass").val() != $("#cnewpass").val()) {
        $("#cnewpassError").text('* Password did not matched!');
        $("#changePassBtn").val('Change Password');
      } else {
        $.ajax({
          url: 'assets/php/process.php',
          method: 'post',
          data: $("#change-pass-form").serialize() + '&action=changepass',
          success: function(response) {
            $("#changepassAler").html(response);
            $("#changePassBtn").val('Change Password');
            $("#cnewpassError").text('');
            $("#change-pass-form")[0].reset();
          }
        });
      }
    }
  });

  //Verify E-mail ajax request
  $("#verify-email").click(function(e) {
    e.preventDefault();
    $(this).text('Please Wait...');
    $.ajax({
      url: 'assets/php/process.php',
      method: 'post',
      data: {
        verify: 'verify_email'
      },
      success: function(response) {
        $("#verifyEmailAlert").html(response);
        $("#verify-email").text('Verify Now');
      }
    });
  });

  checkNotification();

  function checkNotification() {
    $.ajax({
      url: 'assets/php/process.php',
      method: 'post',
      data: {
        action: 'notificationCheck'
      },
      success: function(response) {
        $("#showNotificationCheck").html(response);
      }
    });
  }
});
</script>
<html>
  <head>
    <title>Title of the document</title>
    <style>
      .button {
        background-color: red;
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