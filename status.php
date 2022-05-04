<?php


# STATUS
# /status.php






// ************************************ { 2022-04-29 - RC } ************************************
// LOAD THE CONFIG FILE AND CONNECT TO THE DATABASE
// *********************************************************************************************
require_once 'config.php';

$conn = new PDO("mysql:host=".$settings['db_host'].";dbname=".$settings['db_name'].";",$settings['db_user'],$settings['db_pass']);






// ************************************ { 2022-05-03 - RC } ************************************
// SET THE DEFAULTS
// *********************************************************************************************
$user_hash = false;
if ((isset($_SERVER['REQUEST_URI'])) && (!empty($_SERVER['REQUEST_URI']))) {
	$user_hash = preg_replace('~^.*/status/(.*)$~', '$1', $_SERVER['REQUEST_URI']);
}






// ************************************ { 2022-05-03 - RC } ************************************
// DUMP OUT IF NO ID IS PRESENT
// *********************************************************************************************
if (!$user_hash) {
	$errors[] = 'An ID for the status is required.';
	handle_errors($errors);
	return false;
}





print get_status($user_hash, $errors, $conn);












// ************************************ { 2022-05-03 - RC } ************************************
// FUNCTIONS
// *********************************************************************************************






// ************************************ { 2022-05-03 - RC } ************************************
// GET THE USER'S STATUS FROM THE DATABASE
// *********************************************************************************************
function get_status($user_hash, &$errors, $conn) {



	// ************************************ { 2022-05-03 - RC } ************************************
	// SET THE DEFAULTS
	// *********************************************************************************************
	$status_message = 'Available';






	// ************************************ { 2022-05-03 - RC } ************************************
	// QUERY THE DB FOR THE STATUS
	// *********************************************************************************************
	$q_get_user_hash_information = $conn->prepare("
		SELECT
			statuses.Status_Message
		FROM
			users,
			statuses
		WHERE
			users.User_Hash = :user_hash AND
			users.Reference = statuses.Parent AND
				(
					statuses.Active_Status = 1 OR
					statuses.Default_Status = 1
				)
		ORDER BY
			Active_Status DESC,
			Default_Status DESC
		LIMIT 1
	");

	$q_get_user_hash_information->execute(
		array(
			':user_hash' => $user_hash
		)
	);

	$row_get_user_hash_information = $q_get_user_hash_information->fetchAll(PDO::FETCH_ASSOC);






	// ************************************ { 2022-05-03 - RC } ************************************
	// IF WE HAVE NO RECORD FOR THAT USER, DO A REDIRECT TO THE ERROR PAGE
	// *********************************************************************************************
	if (count($row_get_user_hash_information) == 0) {
		$errors[] = 'No records for the id of '.$user_hash.'. Please check the spelling and try again.';
		handle_errors($errors);
		return;
	}






	// ************************************ { 2022-05-03 - RC } ************************************
	// GRAB THE STATUS MESSAGE
	// *********************************************************************************************
	foreach ($row_get_user_hash_information AS $get_user_hash_information) {

		$status_message = $get_user_hash_information['Status_Message'];

	}



	return $status_message;



}






// ************************************ { 2022-05-02 - RC } ************************************
// LOG THE USER IN OR REDIRECT TO THE REGISTRATION PAGE AND SUPPLY AN ERROR MESSAGE
// *********************************************************************************************
function handle_errors($errors = array()) {



	if (!empty($errors)) {

		$error_string = json_encode('<li>'.implode('<li>', $errors));

		header("Location: ../error.php?error=".$error_string);
		return;

	}



}