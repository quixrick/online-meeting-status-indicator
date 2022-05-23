<?php

// LOGIN
// /login.php






// ************************************ { 2022-04-29 - RC } ************************************
// LOAD THE CONFIG FILE AND CONNECT TO THE DATABASE
// *********************************************************************************************
require_once 'config.php';

$conn = new PDO("mysql:host=".$settings['db_host'].";dbname=".$settings['db_name'].";",$settings['db_user'],$settings['db_pass']);






// ************************************ { 2022-04-29 - RC } ************************************
// SET THE DEFAULTS
// *********************************************************************************************
$errors = array();
$username = '';
$password = '';


$username = $_POST['email_address'];
$password = $_POST['password'];


// print '<br>Username: '.$username;
// print '<br>Password: '.$password;




$password_hash = get_password_hash($username, $password, $errors);
authenticate_user($username, $password, $errors, $conn);




function get_password_hash($username, $password, &$errors) {



	// ************************************ { 2022-04-29 - RC } ************************************
	// MAKE SURE THE USERNAME AND PASSWORD ARE NOT EMPTY
	// *********************************************************************************************
	if ((strlen($username) == 0) || (strlen($password) == 0)) {
		$errors[] = "Username and Password cannot be empty";
		handle_login($errors);
		return;
	}






	// ************************************ { 2022-04-29 - RC } ************************************
	// HASH THE PASSWORD
	// *********************************************************************************************
	$password_hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
	return $password_hash;



}






function handle_login($errors = array()) {



	if (!empty($errors)) {

		$error_string = json_encode('<li>'.implode('<li>', $errors));

		header("Location: index.php?error=".$error_string);
		return;

	}


	header("Location: dashboard.php");
	return;


}
















function authenticate_user($username, $password_hash, &$errors, $conn) {



	// ************************************ { 2022-04-29 - RC } ************************************
	// SET THE DEFAULTS
	// *********************************************************************************************
	$user_db_password = false;






	// ************************************ { 2022-04-29 - RC } ************************************
	// GET USER'S PASSWORD HASH
	// *********************************************************************************************
	$q_get_user_db_password = $conn->prepare("
		SELECT
			Password
		FROM
			users
		WHERE
			Email = :username
	");

	$q_get_user_db_password->execute(
		array(
			':username' => $username
		)
	);

	$row_get_user_db_password = $q_get_user_db_password->fetchAll(PDO::FETCH_ASSOC);

	foreach ($row_get_user_db_password AS $get_user_db_password) {

		$user_db_password = $get_user_db_password['Password'];

	}






	// ************************************ { 2022-04-29 - RC } ************************************
	// CHECK TO MAKE SURE WE HAVE A PASSWORD IN THE DATABASE FOR THIS USER
	// *********************************************************************************************
	if (!$user_db_password) {
		$errors[] = 'No user found in the database.  Please register for an account.';
		handle_login($errors);
		return;
	}






	// ************************************ { 2022-04-29 - RC } ************************************
	// VERIFY THE PASSWORD
	// *********************************************************************************************
	if (password_verify($password_hash, $user_db_password)) {
		handle_login();
		return;
	}






	// ************************************ { 2022-04-29 - RC } ************************************
	// IF WE DO NOT HAVE A VALID PASSWORD, SEND 'EM BACK TO THE LOGIN SCREEN
	// *********************************************************************************************
	$errors[] = 'Username or Password is invalid. Please try logging in again.';
	handle_login($errors);
	return;



}