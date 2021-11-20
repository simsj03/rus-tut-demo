<?php  
	include_once 'assets/php/admin-header.php';
?>

<div class="row">
  <div class="col-lg-12">
    <div class="card my-2 border-success">
      <div class="card-header bg-danger text-white">
        <h4 class="m-0">Total Registered Users</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive" id="showAllUsers">
          <p class="text-center lead align-self-center">Please Wait...</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Display User in details Modal -->
<div class="modal fade" id="showUserDetailsModal">
  <div class="modal-dialog modal-dialog-centered mw-100 w-50">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="getName"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="card-deck">
          <div class="card border-primary">
            <div class="card-body">
              <p id="getEmail"></p>
              <p id="getPhone"></p>
              <p id="getDob"></p>
              <p id="getGender"></p>
              <p id="getCreatedAt"></p>
              <p id="getVerified"></p>
            </div>
          </div>
          <div class="card align-self-center" id="getImage">
          </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- Insert Private URL Modal Start -->
<div class="modal fade" tabindex="-1" id="showSendUrlModal" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Send Private URLs to this User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-4">
        <form action="#" id="url-private-form">
          <div class="form-group">
            <label for="url">Enter URLs</label>
            <textarea name="url" id="url" rows="6" class="form-control" placeholder="Enter your urls here..." required></textarea>
            <div class="small text-muted">You can enter one or more urls here. Use comma (,) to seprate each urls. <br> Eg: https://google.com,https://facebook.com,http://anyurl.com/</div>
          </div>
          <div class="form-group">
            <input type="submit" value="Send" class="btn btn-danger btn-lg btn-block" id="url-btn">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Insert Private URl Modal End -->

<!-- footer end -->

</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {

  checkNotification();

  function checkNotification() {
    $.ajax({
      url: 'assets/php/admin-action.php',
      method: 'post',
      data: {
        action: 'notificationCheck'
      },
      success: function(response) {
        $("#showNotificationCheck").html(response);
      }
    });
  }


  // Display User's details
  $("body").on("click", ".userDetailsIcon", function(e) {
    e.preventDefault();
    details_id = $(this).attr('id');
    $.ajax({
      url: 'assets/php/admin-action.php',
      type: 'post',
      data: {
        details_id: details_id
      },
      success: function(response) {
        data = JSON.parse(response);
        $("#getName").text(data.name + ' ' + '(ID : ' + data.id + ')');
        $("#getEmail").text('Email : ' + data.email);
        $("#getPhone").text('Phone : ' + data.phone);
        $("#getGender").text('Gender : ' + data.gender);
        $("#getDob").text('DOB : ' + data.dob);
        $("#getCreatedAt").text('Joined On : ' + data.created_at);
        $("#getVerified").text('Verified : ' + data.verified);
        if (data.photo != '') {
          $("#getImage").html('<img src="../assets/php/' + data.photo + '" class="img-thumbnail img-fluid align-self-center" width="280px">');
        } else {
          $("#getImage").html('<img src="../assets/img/avatar.png" class="img-thumbnail img-fluid align-self-center" width="280px">');
        }

      }
    });
  });

  // Delete a user ajax request
  $("body").on("click", ".userDeleteIcon", function(e) {
    e.preventDefault();
    del_id = $(this).attr('id');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'assets/php/admin-action.php',
          type: 'post',
          data: {
            del_id: del_id
          },
          success: function(response) {
            console.log(response);
            Swal.fire({
              title: 'Deleted!',
              text: 'User deleted successfully!.',
              icon: 'success'
            })
            fetchAllUsers();
          }
        });
      }
    });
  });


  //Fetch All Users Ajax Request
  fetchAllUsers();

  function fetchAllUsers() {
    $.ajax({
      url: 'assets/php/admin-action.php',
      method: 'post',
      data: {
        action: 'fetchAllUsers'
      },
      success: function(response) {
        $("#showAllUsers").html(response);
        $("table").DataTable({
          order: [0, 'desc']
        });
      }
    });
  }

  // Set user id on click of send url btn
  $(document).on('click', '.sendUrlIcon', function(e) {
    e.preventDefault();
    user_id = $(this).attr('id');
    // console.log(user_id);
  });

  $("#url-private-form").submit(function(e) {
    e.preventDefault();
    let url_text = $("#url").val();
    $("#url-btn").val('Please Wait...');
    $.ajax({
      url: 'assets/php/admin-action.php',
      method: 'post',
      data: {
        user_id: user_id,
        url_text: url_text,
        insert_private_url: 1
      },
      success: function(response) {
        // console.log(response);
        $("#showSendUrlModal").modal('hide');
        $("#url-private-form")[0].reset();
        $("#url-btn").val('Send');
        Swal.fire({
          title: 'URL sent successfully!',
          icon: 'success'
        });
      }
    });
  });

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