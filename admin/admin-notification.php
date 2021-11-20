<?php  
	include_once 'assets/php/guest-header.php';
?>

<div class="row justify-content-center my-2">
  <div class="col-lg-6 mt-4" id="showAllNotification">

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
      url: 'assets/php/guest-action.php',
      method: 'post',
      data: {
        action: 'notificationCheck'
      },
      success: function(response) {
        $("#showNotificationCheck").html(response);
      }
    });
  }


  //Delete notification
  $("body").on("click", ".close", function(e) {
    e.preventDefault();
    notification_id = $(this).attr('id');
    $.ajax({
      url: 'assets/php/admin-action.php',
      method: 'post',
      data: {
        notification_id: notification_id
      },
      success: function(response) {
        fetchAllNotification();
        checkNotification();
      }
    });
  });

  fetchAllNotification();

  function fetchAllNotification() {
    $.ajax({
      url: 'assets/php/guest-action.php',
      method: 'post',
      data: {
        action: 'fetchAllNotification'
      },
      success: function(response) {
        $("#showAllNotification").html(response);
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