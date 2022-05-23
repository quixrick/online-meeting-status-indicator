<?php

# DASHBOARD
# /dashboard.php



// ************************************ { 2022-05-06 - RC } ************************************
//INCLUDE THE JWT FILE 
// *********************************************************************************************
require_once 'jwt.php';






// ************************************ { 2022-04-29 - RC } ************************************
// LOAD THE CONFIG FILE AND CONNECT TO THE DATABASE
// *********************************************************************************************
require_once 'config.php';
require_once 'header.php';
require_once 'footer.php';

$conn = new PDO("mysql:host=".$settings['db_host'].";dbname=".$settings['db_name'].";",$settings['db_user'],$settings['db_pass']);






// ************************************ { 2022-05-03 - RC } ************************************
// SET THE DEFAULTS
// *********************************************************************************************
$errors = array();

if ((isset($_POST['jwt'])) && (!empty($_POST['jwt']))) {
	$jwt = $_POST['jwt'];
}






$user_array = get_jwt_user_info($jwt, $settings['jwt_token'], $errors);






if (verify_user_info($user_array, $errors, $conn)) {
	// GET THE LIST OF STATUSES
	$status_list = get_status_list($user_array, $errors, $conn);
	print header('Content-Type: application/json');
	print json_encode(array('items' => $status_list));
}






function get_status_list($user_array, $errors, $conn) {



	// ************************************ { 2022-05-09 - RC } ************************************
	// SET THE DEFAULTS
	// *********************************************************************************************
	$return_array = array();






	// ************************************ { 2022-05-09 - RC } ************************************
	// PULL THE STATUSES FOR THE USER FROM THE DATABASE
	// *********************************************************************************************
	$q_get_status_list = $conn->prepare("
		SELECT
			Reference,
			Status_Message,
			Status_Start_Utc,
			Status_End_Utc,
			Default_Status,
			Active_Status,
			Status_Format
		FROM
			statuses
		WHERE
			Parent = :parent
		ORDER BY
			Active_Status DESC,
			Default_Status DESC,
			Status_Message ASC
	");

	$q_get_status_list->execute(
		array(
			':parent' => $user_array['user_id']
		)
	);

	$row_get_status_list = $q_get_status_list->fetchAll(PDO::FETCH_ASSOC);

	foreach ($row_get_status_list AS $get_status_list) {



		$status_array = array();
		$status_array['reference'] = $get_status_list['Reference'];
		$status_array['status_message'] = preg_replace('~<br>.*~i', '', $get_status_list['Status_Message']);
		$status_array['status_start'] = $get_status_list['Status_Start_Utc'];
		$status_array['status_end'] = $get_status_list['Status_End_Utc'];
		$status_array['default_status'] = $get_status_list['Default_Status'];
		$status_array['active_status'] = $get_status_list['Active_Status'];
		$status_array['status_format'] = $get_status_list['Status_Format'];



		$return_array[] = $status_array;



	}



	return $return_array;



}








function verify_user_info($user_array, &$errors, $conn) {



	// ************************************ { 2022-05-06 - RC } ************************************
	// SET THE DEFAULTS
	// *********************************************************************************************
	$user_count = 0;






	// ************************************ { 2022-05-06 - RC } ************************************
	// CHECK TO MAKE SURE THE STUFF IN THE TOKEN MATCHES WHAT IS IN THE DATABASE
	// *********************************************************************************************
	$q_verify_user_info = $conn->prepare("
		SELECT
			COUNT(*) AS user_count
		FROM
			users
		WHERE
			Reference = :user_id AND
			Email = :username
	");

	$q_verify_user_info->execute(
		array(
			':user_id' => $user_array['user_id'],
			':username' => $user_array['username']
		)
	);

	$row_verify_user_info = $q_verify_user_info->fetchAll(PDO::FETCH_ASSOC);

	foreach ($row_verify_user_info AS $verify_user_info) {

		$user_count = $verify_user_info['user_count'];

	}



	if ($user_count == 0) {

		$errors[] = 'Authentication Error.<br><br>Please try signing out and back in again. [#da1]';
		return false;

	}



	return true;



}



function get_jwt_user_info($jwt, $server_key, &$errors) {



	// ************************************ { 2016-04-- RC } ************************************
	// VERIFY THE USER FROM THE JAVASCRIPT WEB TOKEN
	// *********************************************************************************************
	try {

		$jwt_object = JWT::decode($jwt, $server_key);

	}
	catch (Exception $e) {

		$errors[] = 'Authentication Error.<br><br>Please try signing out and back in again. [#da2]';
		handle_errors($errors);
		return;

	}



	return (array)$jwt_object;



}






// ************************************ { 2022-05-06 - RC } ************************************
// LOG THE USER IN OR REDIRECT TO THE REGISTRATION PAGE AND SUPPLY AN ERROR MESSAGE
// *********************************************************************************************
function handle_errors($errors = array()) {



	if (!empty($errors)) {

		$error_string = json_encode('<li>'.implode('<li>', $errors));

		header("Location: error.php?error=".$error_string);
		return;

	}



}