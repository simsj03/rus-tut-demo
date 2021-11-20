<?php  
	include_once 'assets/php/header.php';
?>
<div class="container">
  <div class="row justify-content-center my-2">
    <div class="col-lg-12 mt-2">
      <div class="card">
        <div class="card-header d-flex flex-row align-items-end justify-content-between">
          <h5 class="p-0 m-0">Working URLs List</h5>
          <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a href="#private" data-toggle="tab" role="tab" aria-controls="private" aria-selected="true" class="nav-link active" id="private-tab">Private URLs</a>
            </li>
            <li class="nav-item">
              <a href="#public" data-toggle="tab" role="tab" aria-controls="public" aria-selected="false" class="nav-link" id="public-tab">Public URLs</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="myTabContent">

            <div class="tab-pane show active" id="private" role="tabpanel" aria-labelledby="private-tab">
              <h4 class="mt-4 text-center text-secondary">URLs are loading please wait...</h4>
            </div>

            <div class="tab-pane" id="public" role="tabpanel" aria-labelledby="public-tab">
              <h4 class="mt-4 text-center text-danger">URLs are loading please wait...</h4>
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
  // Check Notification
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

  // Fetch All Public URLs
  fetchAllPublicUrls();

  function fetchAllPublicUrls() {
    $.ajax({
      url: 'assets/php/process.php',
      method: 'post',
      data: {
        fetchAllPublicUrls: 1
      },
      success: function(response) {
        $("#public").html(response);
        $("table").DataTable({
          order: [0, 'desc']
        });
      }
    });
  }

  // Fetch All Private URLs of an User
  fetchAllPrivateUrls();

  function fetchAllPrivateUrls() {
    $.ajax({
      url: 'assets/php/process.php',
      method: 'post',
      data: {
        fetchAllPrivateUrls: 1,
        uid: '<?= $cid; ?>'
      },
      success: function(response) {
        $("#private").html(response);
        $("#private-urls").DataTable({
          order: [0, 'desc'],
          stateSave: true,
          "bDestroy": true
        });
      }
    })
  }

  // Delete private url ajax request
  $(document).on('click', '.deleteUrlIcon', function(e) {
    let url_id = $(this).attr('id');
    e.preventDefault();
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
          url: 'assets/php/process.php',
          type: 'post',
          data: {
            delete_url_id: url_id
          },
          success: function(response) {
            console.log(response);
            Swal.fire({
              title: 'Deleted!',
              text: 'URL deleted successfully!.',
              icon: 'success'
            })
            fetchAllPrivateUrls();
          }
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