<?php  
	include_once 'assets/php/admin-header.php';
?>

<div class="row">
  <div class="col-lg-12">
    <div class="card my-2 border-secondary">
      <div class="card-header bg-warning text-white flex-row d-flex justify-content-between align-items-center">
        <h4 class="m-0 text-dark">All URLs</h4>
        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#sendUrlModal"><i class="fas fa-link fa-lg pr-2"></i>Send Public URL</button>
        <a href="#" id="deleteAllUrlsBtn" class="btn btn-warning"><i class="fas fa-trash fa-lg pr-2"></i>Delete All Public URLs</a>

        <a href="#" id="deleteAllPrivateUrlsBtn" class="btn btn-danger"><i class="fas fa-trash fa-lg pr-2"></i>Delete All Private URLs</a>
      </div>
      <div class="card-body">
        <div class="table-responsive" id="showAllUrls">
          <p class="text-center lead align-self-center">Please Wait...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Insert URL Modal Start -->
<!-- <div class="modal fade" tabindex="-1" id="sendUrlModal" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Send URLs To All Users</h5>
        <button type="button" class="close" data-dismiss="modal" a1ria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-4">
        <form action="#" id="url-form">
          <div class="form-group">
            <label for="url">Enter URLs</label>
            <textarea name="url" id="url" rows="6" class="form-control" placeholder="Enter your urls here..." required></textarea>
            <div cklass="small text-muted">You can enter one or more urls here. Use comma (,) to seprate each urls. <br> Eg: https://google.com,https://facebook.com,http://anyurl.com/</div>
          </div>
          <div class="form-group">
            <input type="submit" value="Send" class="btn btn-danger btn-lg btn-block" id="url-btn">
          </div>
        </form>
      </div>
    </div>
  </div>
</div> -->
<!-- Insert URl Modal End -->

<!-- Edit URL Modal Start -->
<div class="modal fade" tabindex="-1" id="urlEditModal" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit This URL</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-4">
        <form action="#" id="url-edit-form">
          <input type="hidden" id="editId">
          <div class="form-group">
            <label for="url">Enter URL</label>
            <input type="url" name="url" id="editUrl" class="form-control form-control-lg" required>
            <div class="small text-muted">Enter text in URL format (prefix http:// or https:// before URL)</div>
          </div>
          <div class="form-group">
            <input type="submit" value="Update" class="btn btn-primary btn-lg btn-block" id="url-edit-btn">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Edit URl Modal End -->

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

  // Send New Url Ajax Request
  $("#url-form").submit(function(e) {
    let url = $("#url").val();
    e.preventDefault();
    $("#url-btn").val('Please Wait...');
    $.ajax({
      url: 'assets/php/admin-action.php',
      method: 'post',
      data: {
        url: url,
        sendUrl: 1
      },
      success: function(response) {
        Swal.fire({
          title: 'URL sent successfully!',
          icon: 'success'
        });
        $("#url-form")[0].reset();
        $("#sendUrlModal").modal('hide');
        $("#url-btn").val('Send');
        fetchAllPublicUrl();
      }
    })
  });

  // Display All Public Urls ajax request
  fetchAllPublicUrl();

  function fetchAllPublicUrl() {
    $.ajax({
      url: 'assets/php/admin-action.php',
      method: 'post',
      data: {
        fetchAllPublicUrl: 1
      },
      success: function(response) {
        $("#showAllUrls").html(response);
        $("table").DataTable({
          order: [0, 'desc']
        });
      }
    });
  }

  // Edit an Url ajax request
  $("body").on("click", ".urlEditIcon", function(e) {
    e.preventDefault();
    edit_url_id = $(this).attr('id');
    $.ajax({
      url: 'assets/php/admin-action.php',
      type: 'post',
      data: {
        edit_url_id: edit_url_id
      },
      success: function(response) {
        data = JSON.parse(response);
        // console.log(data);
        $("#editId").val(data.id);
        $("#editUrl").val(data.url);
      }
    });
  });

  // Update Url Ajax Request
  $("#url-edit-form").submit(function(e) {
    let editUrl = $("#editUrl").val();
    let editId = $("#editId").val();
    e.preventDefault();
    $.ajax({
      url: 'assets/php/admin-action.php',
      method: 'post',
      data: {
        editUrl: editUrl,
        editId: editId
      },
      success: function(response) {
        Swal.fire({
          title: 'URL updated successfully!',
          icon: 'success'
        });
        $("#url-edit-form")[0].reset();
        $("#urlEditModal").modal('hide');
        fetchAllPublicUrl();
      }
    })
  });

  //delete public url ajax request
  // $("body").on("click", ".urlDeleteIcon", function(e) {
  //   e.preventDefault();
  //   del_iurl_id = $(this).attr('id');
  //   Swal.fire({
  //     title: 'Are you sure?',
  //     text: "You won't be able to revert this!",
  //     icon: 'warning',
  //     showCancelButton: true,
  //     confirmButtonColor: '#3085d6',
  //     cancelButtonColor: '#d33',
  //     confirmButtonText: 'Yes, delete it!'
  //   }).then((result) => {
  //     if (result.value) {
  //       $.ajax({
  //         url: 'assets/php/admin-action.php',
  //         type: 'post',
  //         data: {
  //           del_url_id: del_url_id
  //         },
  //         success: function(response) {
  //           // console.log(response);
  //           Swal.fire({
  //             title: 'Deleted!',
  //             text: 'URL deleted successfully!.',
  //             icon: 'success'
  //           })
  //           fetchAllPublicUrl();
  //         }
  //       });
  //     }
  //   });
  // });

  //delete all public url ajax request
  $("#deleteAllUrlsBtn").click(function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete all!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'assets/php/admin-action.php',
          type: 'post',
          data: {
            delete_all_urls: 1
          },
          success: function(response) {
            // console.log(response);
            Swal.fire({
              title: 'Deleted!',
              text: 'All URLs deleted successfully!.',
              icon: 'success'
            })
            fetchAllPublicUrl();
          }
        });
      }
    });
  });

  //delete all private url ajax request
  $("#deleteAllPrivateUrlsBtn").click(function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete all!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'assets/php/admin-action.php',
          type: 'post',
          data: {
            delete_all_private_urls: 1
          },
          success: function(response) {
            // console.log(response);
            Swal.fire({
              title: 'Deleted!',
              text: 'All URLs deleted successfully!.',
              icon: 'success'
            })
            fetchAllPublicUrl();
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