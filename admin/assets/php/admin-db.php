<?php  
	require_once dirname(__DIR__, 3) . '/config/config.php';

	class Admin extends Database {
	  //Admin Login
	  public function admin_login($username,$password) {
	    $sql = 'SELECT username, password FROM admin WHERE username = :username AND password = :password';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['username' => $username, 'password' => $password]);
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	    return $row;
	  }

	  //Count Total Users
	  public function totalCount($tablename) {
	    $sql = "SELECT * FROM $tablename";
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute();
	    $count = $stmt->rowCount();
	    return $count;
	  }

	  //Count Total Verified Users
	  public function verified_users($status) {
	    $sql = 'SELECT * FROM users WHERE verified = :status';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['status' => $status]);
	    $count = $stmt->rowCount();
	    return $count;
	  }

	  //Count Site Hits
	  public function site_hits() {
	    $sql = 'SELECT hits FROM visitors';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute();

	    $count = $stmt->fetch(PDO::FETCH_ASSOC);
	    return $count;
	  }

	  //Fetch All Registered Users
	  public function fetchAllUsers($val) {
	    $sql = "SELECT * FROM users WHERE deleted != $val";
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute();
	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    return $result;
	  }

	  //Fetch User Details by ID
	  public function fetchUserDetailsByID($id) {
	    $sql = 'SELECT * FROM users WHERE id = :id AND deleted != 0';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);

	    $result = $stmt->fetch(PDO::FETCH_ASSOC);
	    return $result;
	  }

	  // Delete a user
	  public function userAction($id,$val) {
	    $sql = "UPDATE users SET deleted = $val WHERE id = :id";
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);

	    return true;
	  }

	  //Export All users to excel
	  public function exportAllUsers() {
	    $sql = 'SELECT * FROM users';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute();
	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    return $result;
	  }

	  //Fetch all notes with user info
	  public function fetchAllNotes() {
	    $sql = 'SELECT notes.id, notes.title, notes.note, notes.created_at, notes.updated_at, users.name, users.email FROM notes INNER JOIN users ON notes.uid = users.id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute();
	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    return $result;
	  }

	  //Delete a note of a user by admin
	  public function deleteNoteOfUser($id) {
	    $sql = 'DELETE FROM notes WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);

	    return true;
	  }

	  //Fetch all notification
	  public function fetchNotification() {
	    $sql = "SELECT notification.id, notification.message, notification.created_at, users.name, users.email FROM notification INNER JOIN users ON notification.uid = users.id WHERE type = 'admin' ORDER BY notification.id DESC LIMIT 5";
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute();
	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    return $result;
	  }

	  //Remove notification
	  public function removeNotification($id) {
	    $sql = "DELETE FROM notification WHERE id = :id AND type = 'admin'";
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);

	    return true;
	  }

	  // Fetch all feedback
	  public function fetchFeedback() {
	    $sql = 'SELECT feedback.id, feedback.subject, feedback.feedback, feedback.created_at, feedback.uid, users.name, users.email FROM feedback INNER JOIN users ON feedback.uid = users.id WHERE replied != 1 ORDER BY feedback.id DESC';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute();
	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    return $result;
	  }

	  //Reply to user
	  public function replyFeedback($uid,$message) {
	    $sql = "INSERT INTO notification (uid,type,message) VALUES (:uid,'user',:message)";
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['uid' => $uid, 'message' => $message]);
	    return true;
	  }

	  //Set Notification replied
	  public function feedbackReplied($fid) {
	    $sql = 'UPDATE feedback SET replied = 1 WHERE id = :fid';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['fid' => $fid]);
	    return true;
	  }

	  // Insert New URL Public
	  public function insertUrl($uid = null, $url, $status) {
	    $sql = 'INSERT INTO urls (uid, url, status) VALUES (:uid, :url, :status)';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['uid' => $uid, 'url' => $url, 'status' => $status]);
	    return true;
	  }

	  // Fetch All Public Url
	  public function fetchAllPublicUrl() {
	    $sql = 'SELECT * FROM urls WHERE deleted != 0';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute();
	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	    return $result;
	  }

	  // Fetch Url by id
	  public function fetchUrlById($id) {
	    $sql = 'SELECT * FROM urls WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    $result = $stmt->fetch(PDO::FETCH_ASSOC);
	    return $result;
	  }

	  // Update a public url
	  public function updatePublicUrl($id, $url) {
	    $sql = 'UPDATE urls SET url = :url, updated_at = NOW() WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id, 'url' => $url]);
	    return true;
	  }

	  // Delete an public/private url
	  public function deleteAnUrl($id) {
	    $sql = 'DELETE FROM urls WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    return true;
	  }

	  // Delete all public/private urls
	  public function deleteAllUrls($status) {
	    $sql = 'DELETE FROM urls WHERE status = :status';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['status' => $status]);
	    return true;
	  }

	  // Fetch all deleted urls by user
	  public function fetchAllDeletedUrls() {
	    $sql = 'SELECT users.id, users.name, users.email, urls.id, urls.uid, urls.url, urls.status, urls.deleted_at FROM urls INNER JOIN users ON urls.uid = users.id WHERE urls.deleted != 1 AND urls.status = :status ORDER BY urls.id DESC';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['status' => 'private']);
	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    return $result;
	  }

	  // Delete deleted urls by admin
	  public function deleteADeletedUrl($id) {
	    $sql = 'DELETE FROM urls WHERE status = :status AND deleted != 1 AND id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['status' => 'private', 'id' => $id]);
	    return true;
	  }

	  // Delete all deleted urls by admin
	  public function deleteAllDeletedUrls() {
	    $sql = 'DELETE FROM urls WHERE status = :status AND deleted != 1';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['status' => 'private']);
	    return true;
	  }
	}
?>
