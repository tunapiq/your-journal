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
	case 'researcher':
	performResearcherAction();
	break;
	default: break;
}

}


if($user['role'] == 'administrator'){
	//load admin dashboard data
	$user_types = [
		'administrator' => $conn -> query("SELECT user.*,administrator.id as role_id FROM user,administrator WHERE user.id=administrator.user_id"),
		'editor' => $conn -> query("SELECT user.*,editor.id as role_id FROM user,editor WHERE user.id=editor.user_id"),
		'researcher' => $conn -> query("SELECT user.*,researcher.id as role_id FROM user,researcher WHERE user.id=researcher.user_id"),
		'reviewer' => $conn -> query("SELECT user.*,reviewer.id as role_id FROM user,reviewer WHERE user.id=reviewer.user_id"),
	];

	$papers  = $conn -> query("SELECT * FROM paper ORDER BY `id` DESC");
	$editors = $conn -> query("SELECT ed.id, usr.name FROM editor AS ed LEFT JOIN user as usr ON usr.id = ed.user_id ORDER BY ed.id DESC");
	$paper_editors = $conn -> query("SELECT pe.*,u.name AS editor_name,p.title AS paper_title FROM paper_editor AS pe, paper AS p, editor AS e, user as u WHERE u.id = e.user_id AND p.id = pe.paper_id AND e.id = pe.editor_id");

}else if($user['role'] == 'researcher'){
  //load researcher dashboard data
	$researcher_papers = $conn -> query("SELECT * FROM paper WHERE researcher_id = '".$user['data']['role_id']."' ORDER BY `id` DESC");

	$paper_editors = $conn -> query("SELECT pe.*,u.name AS editor_name,p.title AS paper_title FROM paper_editor AS pe, paper AS p, editor AS e, user as u WHERE p.researcher_id = {$user['data']['role_id']} AND u.id = e.user_id AND p.id = pe.paper_id AND e.id = pe.editor_id");
}




//close db connection
$conn->close();

// The first argument to bind_param may be one of four types for each param:
// 	i - integer
// 	d - double
// 	s - string
// 	b - BLOB

function performResearcherAction(){
	global $conn, $user;
	switch ($_POST['action']) {
		case 'create_paper':
		$title      = $_POST["title"]??NULL;
		$author     = $_POST["author"]??NULL;
		$article    = $_POST["article"]??NULL;
		$statement = $conn -> prepare("INSERT INTO `paper`(`title`, `author`, `article`, `researcher_id`) VALUES (?, ?, ?, ?)");
		$statement -> bind_param("sssi", $title, $author, $article, $user['data']['role_id']);
		//run the sql
		if($statement -> execute() === TRUE){
			//add one time alert to session
			addSuccessAlertToSession("Paper submitted.");
		}else{
			//add one time alert to session
			addErrorAlertToSession("Unable to submit paper. {$conn->error}");
		}
		$statement -> close();
		doExitRedirect("dashboard.php?tab=1", $conn);
		// case 'delete_user':
		// $user_id      = $_POST["id"]??NULL;
		// $statement = $conn -> prepare("DELETE FROM `user` WHERE `id` = ?");
		// $statement -> bind_param("i", $user_id);
		// //run the sql
		// if($statement -> execute() === TRUE){
		// 	//add one time alert to session
		// 	addSuccessAlertToSession("User deleted.");
		// }else{
		// 	//add one time alert to session
		// 	addErrorAlertToSession("Unable to delete user. {$conn->error}");
		// }
		// $statement -> close();
		// break;

		default: break;
	}
}

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
				addSuccessAlertToSession("$user_type added.");
			}else{
				//add one time alert to session
				addErrorAlertToSession("Unable to add $user_type. {$conn->error}");
			}
			$statement -> close();
			doExitRedirect("dashboard.php?tab=1", $conn);

			case 'delete_user':
			$user_id      = $_POST["id"]??NULL;
			$statement = $conn -> prepare("DELETE FROM `user` WHERE `id` = ?");
			$statement -> bind_param("i", $user_id);
			//run the sql
			if($statement -> execute() === TRUE){
				//add one time alert to session
				addSuccessAlertToSession("User deleted.");
			}else{
				//add one time alert to session
				addErrorAlertToSession("Unable to delete user. {$conn->error}");
			}
			$statement -> close();
			doExitRedirect("dashboard.php?tab=2", $conn);

			case 'assign_editor':
			$editor_id = $_POST["editor_id"]??NULL;
			$paper_id  = $_POST["paper_id"]??NULL;
			$statement = $conn -> prepare("INSERT INTO `paper_editor`(`paper_id`, `editor_id`) VALUES (?, ?)");
			$statement -> bind_param("ii", $paper_id, $editor_id);
			//run the sql
			if($statement -> execute() === TRUE){
				//add one time alert to session
				addSuccessAlertToSession("Editor assigned to paper.");
			}else{
				//add one time alert to session
				addErrorAlertToSession("Unable to assign editor to paper. {$conn->error}");
			}
			$statement -> close();
			doExitRedirect("dashboard.php?tab=3", $conn);

			case 'unassign_editor':
			$editor_id = $_POST["editor_id"]??NULL;
			$paper_id  = $_POST["paper_id"]??NULL;
			$statement = $conn -> prepare("DELETE FROM `paper_editor` WHERE `paper_id` = ? AND `editor_id` = ?");
			$statement -> bind_param("ii", $paper_id, $editor_id);
			//run the sql
			if($statement -> execute() === TRUE){
				//add one time alert to session
				addSuccessAlertToSession("Paper editor unassigned.");
			}else{
				//add one time alert to session
				addErrorAlertToSession("Unable to unassigned paper editor. {$conn->error}");
			}
			$statement -> close();
			doExitRedirect("dashboard.php?tab=3", $conn);

			default: break;
		}
}

?>
