<?php
//include base conroller (includes configs,$user variable, constants, convinient functions and starts php session)
include_once('controller.php');

//don not cache page
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

//if user is not already logged in
if(!sessionHasAuthenticatedUser())
{
  //redirect to login page
	header("Location: index.php");
}

$page_title = ucfirst($user['role'])." Dashboard";

//assertion: user is authenticated.

//connect to db
$conn = new mysqli($db_host_name, $db_username, $db_pass, $db_name);

// check connection
if ($conn->connect_error) {
	//thow error and stop php script execution
	die("Connection failed: " . $conn->connect_error);
}

///assertion: user is authorized to perform this action.

//set prefered connection encoding
$conn -> set_charset('utf8');

//user is trying to perform some action.
if(!empty($_POST)){

	//check to see if user is autorized to perform this post action
	if(!authorized($_POST, $user['role'])){
		//assertion: user is not authorized
		header('HTTP/1.0 403 Forbidden');
		die('You are not autorized to perform this action.');
	}

//determine user role and attempt to perform the appropriate action.
switch ($user['role']) {
	case 'administrator':
	performAdminAction();
	break;
	default: break;
}

}

$user_types = [
	'administrator' => $conn -> query("SELECT user.* FROM user,administrator WHERE user.id=administrator.user_id"),
	'editor' => $conn -> query("SELECT user.* FROM user,editor WHERE user.id=editor.user_id"),
	'researcher' => $conn -> query("SELECT user.* FROM user,researcher WHERE user.id=researcher.user_id"),
	'reviewer' => $conn -> query("SELECT user.* FROM user,reviewer WHERE user.id=reviewer.user_id"),
];



//close db connection
$conn->close();

// The first argument to bind_param may be one of four types for each param:
// 	i - integer
// 	d - double
// 	s - string
// 	b - BLOB

function performAdminAction(){
	global $conn;
		switch ($_POST['action']) {
			case 'create_user':
			$name      = $_POST["name"]??NULL;
			$phone     = $_POST["phone"]??NULL;
			$email     = $_POST["email"]??NULL;
			$pass      = $_POST["password"]??NULL;
			$user_type = $_POST["user_type"]??NULL;

			//ensure a valid usertype is specified
			if(!in_array($user_type, ['administrator','researcher','editor','reviewer'])) die('invalid role specified.');

			$statement = $conn -> prepare("INSERT INTO `user`(`name`, `phone`, `email`, `password`) VALUES (?, ?, ?, ?)");
			$statement -> bind_param("ssss", $name, $phone, $email, $pass);
			//run the sql
			if($statement -> execute() === TRUE){
				$user_id   = $conn -> insert_id;
				$statement = $conn -> prepare("INSERT INTO `$user_type` (`user_id`) VALUES (?)");
				$statement -> bind_param("i", $user_id);
				$statement -> execute();
				//add one time alert to session
				addSuccessAlertToSession("$user_type added successfully.");
			}else{
				//add one time alert to session
				addErrorAlertToSession("Unable to add $user_type. {$conn->error}");
			}
			$statement -> close();
			break;
			case 'delete_user':
			$user_id      = $_POST["id"]??NULL;
			$statement = $conn -> prepare("DELETE FROM `user` WHERE `id` = ?");
			$statement -> bind_param("i", $user_id);
			//run the sql
			if($statement -> execute() === TRUE){
				//add one time alert to session
				addSuccessAlertToSession("User deleted successfully.");
			}else{
				//add one time alert to session
				addErrorAlertToSession("Unable to delete user. {$conn->error}");
			}
			$statement -> close();
			break;

			default: break;
		}
}

?>
