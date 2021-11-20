<?php  
	include_once 'assets/php/admin-header.php';
?>

<div class="row">
  <div class="col-lg-12">
    <div class="card my-2 border-danger">
      <div class="card-header bg-danger text-white">
        <h4 class="m-0">Total Deleted Users</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive" id="showAllDeletedUsers">
          <p class="text-center lead align-self-center">Please Wait...</p>
        </div>
      </div>
    </div>
  </div>
</div>

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

  // Delete a user ajax request
  $("body").on("click", ".userRestoreIcon", function(e) {
    e.preventDefault();
    res_id = $(this).attr('id');
    Swal.fire({
      title: 'Are you sure want to restore this user?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, restore it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'assets/php/admin-action.php',
          type: 'post',
          data: {
            res_id: res_id
          },
          success: function(response) {
            console.log(response);
            Swal.fire({
              title: 'Restored!',
              text: 'User restored successfully!.',
              icon: 'success'
            })
            fetchAllDeletedUsers();
          }
        });
      }
    });
  });

  fetchAllDeletedUsers();

  function fetchAllDeletedUsers() {
    $.ajax({
      url: 'assets/php/admin-action.php',
      method: 'post',
      data: {
        action: 'fetchDeletedUsers'
      },
      success: function(response) {
        $("#showAllDeletedUsers").html(response);
        $("table").DataTable({
          order: [0, 'desc']
        });
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