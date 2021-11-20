<?php  
	include_once 'assets/php/admin-header.php';
	include_once 'assets/php/admin-db.php';
  $count = new Admin();
?>
<div class="row">
  <div class="col-lg-12">
    <div class="card-deck mt-3 text-light text-center font-weight-bold">
      <div class="card bg-red">
        <div class="card-header">Total User</div>
        <div class="card-body">
          <h1 class="display-4">
            <?= $count->totalCount('users'); ?>
          </h1>
        </div>
      </div>
      <div class="card bg-red">
        <div class="card-header">Verified User</div>
        <div class="card-body">
          <h1 class="display-4">
            <?= $count->verified_users(1); ?>
          </h1>
        </div>
      </div>
      <div class="card bg-red">
        <div class="card-header">Unverified User</div>
        <style>
          .card-header{
            color:white;
            background: grey;
          }
          .card-body{
            background: white;
            color:black;
          }
        </style>
        <div class="card-body">
          <h1 class="display-4">
            <?= $count->verified_users(0); ?>
          </h1>
        </div>
      </div>

    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card-deck mt-3 text-light text-center font-weight-bold">
      <div class="card bg-red">
        <div class="card-header">Total Notes</div>
        <div class="card-body">
          <h1 class="display-4">
            <?= $count->totalCount('notes'); ?>
          </h1>
        </div>
      </div>
      <div class="card bg-success">
        <div class="card-header">Total Feedback</div>
        <div class="card-body">
          <h1 class="display-4">
            <?= $count->totalCount('feedback'); ?>
          </h1>
        </div>
      </div>
      <div class="card bg-info">
        <div class="card-header">Total Notification</div>
        <div class="card-body">
          <h1 class="display-4">
            <?= $count->totalCount('notification'); ?>
          </h1>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer Start -->
</div>
</div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
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


google.charts.load("current", {
  packages: ["corechart"]
});
google.charts.setOnLoadCallback(colChart);

function colChart() {
  var data = google.visualization.arrayToDataTable([
    ['Verified', 'Number'],
    <?php  
    	$verified = $count->isVerifiedPer();
    	foreach ($verified as $row) {
    	  if ($row['verified'] == 0) {
    	    $row['verified'] = 'Unverified';
    	  } else {
    	    $row['verified'] = 'Verified';
    	  }
    	  echo '["' . $row['verified'] . '",' . $row['number'] . '],';
    	}
    ?>
  ]);
  var options = {
    pieHole: 0.4,
  };
}
</script>
<html>
  <head>
    <title>Title of the document</title>
    <style>
      .button {
        background-color: grey;
        border: none;
        color: white;
        padding: 15px 25px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 12px;
        margin: 4px 2px;
        cursor: pointer;
      }
    </style>
  </head>
  <body>
    <a href="https://docs.google.com/document/d/1aaWDsmpl2Oi2U7GuGyhtiQ7WQYhG0hloqyk8lmdEEfs/edit?usp=sharing" class="button">Need Help?</a>
  </body>
</html>