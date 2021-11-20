<?php  
	include_once 'assets/php/header.php';
?>
<style>
     .card{
       color:grey;
     }
     .card-header{
       background: red;
     }
    </style>
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <?php if ($verified == 'Not Verified!'): ?>
      <div class="alert alert-danger alert-dismissible text-center mt-2 m-0">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Your E-mail is not verified yet. Please go to profile and verify your E-mail now.</strong>
      </div>
      <?php endif; ?>
      <h4 class="text-center text-danger mt-2">Send a note to admin.</h4>
    </div>
  </div>
  <div class="card">
    <h5 class="card-header bg-danger d-flex justify-content-between">
      <span class="text-light align-self-center lead">All Notes</span>
      <a href="#" data-toggle="modal" data-target="#addNoteModal" class="btn btn-light"><i class="fas fa-plus-circle fa-lg"></i>&nbsp;Send New Note</a>
    </h5>
    <div class="card-body">
      <div class="table-responsive" id="showNotes">
        <p class="text-center lead mt-5">Please Wait...</p>
      </div>
    </div>
  </div>
</div>
<!-- Add New Note Modal -->
<div class="modal fade" id="addNoteModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h4 class="modal-title text-light">Send New Note</h4>
        <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="#" method="post" id="add-note-form" class="px-3">
          <div class="form-group">
            <input type="text" name="title" class="form-control form-control-lg" placeholder="Enter Title" required>
          </div>
          <div class="form-group">
            <textarea name="note" class="form-control form-control-lg" placeholder="Write Your Note Here..." rows="6" required></textarea>
          </div>
          <div class="form-group">
            <input type="submit" name="addNote" id="addNoteBtn" value="Send Report" class="btn btn-danger btn-block btn-lg">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End Add New Note Modal -->

<!-- Update Note Modal -->
<div class="modal fade" id="editNoteModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title text-light">Edit Report</h4>
        <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="#" method="post" id="edit-note-form" class="px-3">
          <input type="hidden" name="id" id="id">
          <div class="form-group">
            <input type="text" name="title" id="title" class="form-control form-control-lg" placeholder="Enter Title" required>
          </div>
          <div class="form-group">
            <textarea name="note" id="note" class="form-control form-control-lg" placeholder="Write Your Note Here..." rows="6" required></textarea>
          </div>
          <div class="form-group">
            <input type="submit" name="editNote" id="editNoteBtn" value="Update Note" class="btn btn-info btn-block btn-lg">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End Update Note Modal -->
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

  //Add New Note Ajax Request
  $("#addNoteBtn").click(function(e) {
    if ($("#add-note-form")[0].checkValidity()) {
      e.preventDefault();
      $("#addNoteBtn").val('Please Wait...');

      $.ajax({
        url: 'assets/php/process.php',
        method: 'post',
        data: $("#add-note-form").serialize() + '&action=add_note',
        success: function(response) {
          $("#addNoteModal").modal('hide');
          $("#add-note-form")[0].reset();
          $("#addNoteBtn").val('Add Note');
          Swal.fire({
            title: 'Note sent successfully to admin!',
            icon: 'success'
          });
          displayAllNotes();
        }
      });
    }
  });

  //delete note of a user ajax request
  $("body").on("click", ".deleteBtn", function(e) {
    e.preventDefault();
    var tr = $(this).closest('tr');
    del_id = $(this).attr('id');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to reverse this!",
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
            del_id: del_id
          },
          success: function(response) {
            tr.css('background-color', '#ff6666');
            Swal.fire({
              title: 'Deleted!',
              text: 'Note deleted successfully!.',
              icon: 'success'
            })
            displayAllNotes();
          }
        });
      }
    });
  });

  // Edit note of a user
  $("body").on("click", ".editBtn", function(e) {
    e.preventDefault();
    edit_id = $(this).attr('id');
    $.ajax({
      url: 'assets/php/process.php',
      type: 'post',
      data: {
        edit_id: edit_id
      },
      success: function(response) {
        data = JSON.parse(response);
        $("#id").val(data.id);
        $("#title").val(data.title);
        $("#note").val(data.note);
      }
    });
  });

  //Update note of a user
  $('#editNoteBtn').click(function(e) {
    if ($("#edit-note-form")[0].checkValidity()) {
      e.preventDefault();
      $.ajax({
        url: 'assets/php/process.php',
        type: 'post',
        data: $('#edit-note-form').serialize() + "&action=update_note",
        success: function(response) {
          Swal.fire({
            title: 'Report updated successfully!',
            icon: 'success'
          })
          $("#editNoteModal").modal('hide');
          $("#edit-note-form")[0].reset();
          displayAllNotes();
        }
      });
    }
  });
  // Display note in details
  $("body").on("click", ".infoBtn", function(e) {
    e.preventDefault();
    info_id = $(this).attr('id');
    $.ajax({
      url: 'assets/php/process.php',
      type: 'post',
      data: {
        info_id: info_id
      },
      success: function(response) {
        data = JSON.parse(response);
        Swal.fire({
          title: '<strong>Report : ID(' + data.id + ')</strong>',
          icon: 'info',
          html: '<b>Title : </b>' + data.title + '<br><br><b>Report : </b>' + data.note + '<br><br><b>Written On : </b>' + data.created_at + '<br><br><b>Updated On : </b>' + data.updated_at,
          showCloseButton: true,
        })
      }
    });
  });

  displayAllNotes();

  // Display All Note of A User
  function displayAllNotes() {
    $.ajax({
      url: 'assets/php/process.php',
      type: 'post',
      data: {
        action: 'disp_all_notes'
      },
      success: function(response) {
        $("#showNotes").html(response);
        $("table").DataTable({
          order: [0, 'desc']
        });
      }
    });
  }

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
    <title>User Manual</title>
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
