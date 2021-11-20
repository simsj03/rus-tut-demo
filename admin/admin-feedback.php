<?php  
	include_once 'assets/php/admin-header.php';
?>

<div class="row justify-content-center">
  <div class="col-lg-12 my-2">
    <div class="card my-2 border-warning">
      <div class="card-header bg-danger text-dark">
        <h4 class="m-0">Total Feedback From Users</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive" id="showAllFeedback">
          <p class="text-center lead align-self-center">Please Wait...</p>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Reply Feedback Modal -->
<div class="modal fade" id="showReplyModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Reply This Feedback!</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <form method="post" action="#" class="px-3" id="feedback-reply-form">
          <div class="form-group">
            <textarea name="message" id="message" class="form-control" rows="6" placeholder="Write your message here..." required></textarea>
          </div>
          <div class="form-group">
            <input type="submit" name="submit" value="Send Reply" class="btn btn-primary btn-block" id="feedback-reply-btn">
          </div>
        </form>
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

  //get the current row user id
  var uid;
  var fid;
  $("body").on("click", ".feedbackReplyIcon", function(e) {
    uid = $(this).attr('id');
    fid = $(this).attr('fid');
  });

  //Send feedback reply to user ajax request  
  $("#feedback-reply-btn").click(function(e) {
    if ($("#feedback-reply-form")[0].checkValidity()) {
      let message = $("#message").val();
      e.preventDefault();
      $("#feedback-reply-btn").val('Please Wait...');
      $.ajax({
        url: 'assets/php/admin-action.php',
        method: 'post',
        data: {
          uid: uid,
          message: message,
          fid: fid
        },
        success: function(response) {
          console.log(fid);
          $("#feedback-reply-btn").val('Send Reply');
          $("#showReplyModal").modal('hide');
          $("#feedback-reply-form")[0].reset();
          Swal.fire(
            'Sent!',
            'Reply sent successfully to the user!',
            'success'
          )
          fetchAllFeedback();
        }
      });
    }

  });

  //Fetch All Feedback Ajax Request
  fetchAllFeedback();

  function fetchAllFeedback() {
    $.ajax({
      url: 'assets/php/admin-action.php',
      method: 'post',
      data: {
        action: 'fetchAllFeedback'
      },
      success: function(response) {
        $("#showAllFeedback").html(response);
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