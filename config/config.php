<?php  
	class Database {
		// PHPMailer Credentials Start
	  const USERNAME = '';
	  const PASSWORD = '';
	  // PHPMailer Credentials End

	  // Database Credentials Start
	  private const DBHOST = '127.0.0.1';
	  private const DBUSER = 'root';
	  private const DBPASS = '';
	  private const DBNAME = 'user_system';
	  // Database Credentials End

	  // Data Source Network
	  private $dsn = 'mysql:host=' . self::DBHOST . ';dbname=' . self::DBNAME . '';

	  // Connection Variable
	  public $conn = null;

	  // Constructor Method For Database Connectivity
	  public function __construct() {
	    try {
	      $this->conn = new PDO($this->dsn,self::DBUSER, self::DBPASS);
	    } catch (PDOException $e) {
	      echo 'Error : ' . $e->getMessage();
	    }
	    return $this->conn;
	  }

	  // Check Inputs Method
	  public function test_input($data) {
	    $data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    return $data;
	  }

	  // Error Success Message function
	  public function showMessage($type,$message) {
	    return '<div class="alert alert-' . $type . ' alert-dismissible">
							  <button type="button" class="close" data-dismiss="alert">&times;</button>
							  <strong class="text-center">' . $message . '</strong>
							</div>';
	  }

	  // Time In Ago Method
	  public function timeAgo($time_ago) {
	    date_default_timezone_set('America/Chicago');
	    $time_ago = strtotime($time_ago) ? strtotime($time_ago) : $time_ago;
	    $time = time() - $time_ago;

	    switch ($time):
			// seconds
			case $time <= 60;
	    return 'Just Now!';
	    // minutes
	    case $time >= 60 && $time < 3600;
	    return (round($time / 60) == 1) ? 'a minute ago' : round($time / 60) . ' minutes ago';
	    // hours
	    case $time >= 3600 && $time < 86400;
	    return (round($time / 3600) == 1) ? 'a hour ago' : round($time / 3600) . ' hours ago';
	    // days
	    case $time >= 86400 && $time < 604800;
	    return (round($time / 86400) == 1) ? 'a day ago' : round($time / 86400) . ' days ago';
	    // weeks
	    case $time >= 604800 && $time < 2600640;
	    return (round($time / 604800) == 1) ? 'a week ago' : round($time / 604800) . ' weeks ago';
	    // months
	    case $time >= 2600640 && $time < 31207680;
	    return (round($time / 2600640) == 1) ? 'a month ago' : round($time / 2600640) . ' months ago';
	    // years
	    case $time >= 31207680;
	    return (round($time / 31207680) == 1) ? 'a year ago' : round($time / 31207680) . ' years ago' ;

	    endswitch;
	  }
	}
?>
