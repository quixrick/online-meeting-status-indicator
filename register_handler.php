<?php

// REGISTRATION HANDLER
// /register_handler.php






// ************************************ { 2022-04-29 - RC } ************************************
// LOAD THE CONFIG FILE AND CONNECT TO THE DATABASE
// *********************************************************************************************
require_once 'config.php';

$conn = new PDO("mysql:host=".$settings['db_host'].";dbname=".$settings['db_name'].";",$settings['db_user'],$settings['db_pass']);






// ************************************ { 2022-04-29 - RC } ************************************
// SET THE DEFAULTS
// *********************************************************************************************
$errors = array();
$first_name = '';
$last_name = '';
$email_address = '';
$password = '';

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email_address = $_POST['email_address'];
$password = $_POST['password'];



if ((strlen($email_address) == 0) || (strlen($email_address) == 0)) $errors[] = 'Email address is required';
if ((strlen($password) == 0) || (strlen($password) == 0)) $errors[] = 'Setting a password is required';
if ((strlen($first_name) == 0) || (strlen($first_name) == 0)) $errors[] = 'First name is required';
if ((strlen($last_name) == 0) || (strlen($last_name) == 0)) $errors[] = 'Last name is required';









if (check_if_user_exists($email_address, $errors, $conn)) {



	$password_hash = get_password_hash($email_address, $password, $errors);
	$user_hash = generate_user_hash($conn);
	add_user($first_name, $last_name, $email_address, $password_hash, $user_hash, $errors, $conn);



}



handle_login($errors);












// ************************************ { 2022-05-02 - RC } ************************************
// FUNCTIONS
// *********************************************************************************************



// ************************************ { 2022-05-02 - RC } ************************************
// CHECK TO SEE IF THE USER ALREADY HAS AN ACCOUNT
// *********************************************************************************************
function check_if_user_exists($email_address, &$errors, $conn) {



	// ************************************ { 2022-05-02 - RC } ************************************
	// SET THE DEFAULTS
	// *********************************************************************************************
	$user_count = 0;






	// ************************************ { 2022-05-02 - RC } ************************************
	// LOOK UP HOW MANY RECORDS EXIST WITH THE SUPPLIED EMAIL ADDRESS
	// *********************************************************************************************
	$q_check_if_user_exists = $conn->prepare("
		SELECT
			COUNT(*) AS user_count
		FROM
			users
		WHERE
			Email = :email_address
	");

	$q_check_if_user_exists->execute(
		array(
			':email_address' => $email_address
		)
	);

	$row_check_if_user_exists = $q_check_if_user_exists->fetchAll(PDO::FETCH_ASSOC);

	foreach ($row_check_if_user_exists AS $check_if_user_exists) {

		$user_count = $check_if_user_exists['user_count'];

	}





	// ************************************ { 2022-05-02 - RC } ************************************
	// WE CAN CREATE A NEW USER; SEND IT!
	// *********************************************************************************************
	if ($user_count == 0) return true;






	// ************************************ { 2022-05-02 - RC } ************************************
	// RETURN AN ERROR
	// *********************************************************************************************
	$errors[] = 'There is already account for this user.  Please <a href="index.php">log in</a>.';
	return false;



}



// ************************************ { 2022-05-02 - RC } ************************************
// CREATE THE DATABASE RECORD FOR THE USER
// *********************************************************************************************
function add_user($first_name, $last_name, $email_address, $password_hash, $user_hash, &$errors, $conn) {



	$q_add_user = $conn->prepare("
		INSERT INTO
			users
		SET
			Reference = :reference,
			Email = :email_address,
			First_Name = :first_name,
			Last_Name = :last_name,
			Password = :password,
			User_Hash = :user_hash
			
	");

	$q_add_user->execute(
		array(
			':reference' => 0,
			':email_address' => $email_address,
			':first_name' => $first_name,
			':last_name' => $last_name,
			':password' => $password_hash,
			':user_hash' => $user_hash
		)
	);



	return true;



}






// ************************************ { 2022-05-02 - RC } ************************************
// GENERATE A USER HASH FOR THE SHARABLE URL
// *********************************************************************************************
function generate_user_hash($conn) {



	// ************************************ { 2022-05-02 - RC } ************************************
	// SET THE DEFAULTS
	// *********************************************************************************************
	$desired_string_length = 6;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$user_hash = '';
	$is_id_unique = false;





	do {



		// ************************************ { 2022-05-02 - RC } ************************************
		// LOOP THROUGH AND PICK OUT A CHARACTER
		// *********************************************************************************************
		for ($i = 0; $i < $desired_string_length; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$user_hash .= $characters[$index];
		}






		// ************************************ { 2022-05-02 - RC } ************************************
		// CHECK TO SEE IF THIS ID ALREADY EXISTS IN THE DATABSE
		// *********************************************************************************************
		$q_check_if_id_exists = $conn->prepare("
			SELECT
				COUNT(*) AS id_count
			FROM
				users
			WHERE
				User_Hash = :user_hash
		");

		$q_check_if_id_exists->execute(
			array(
				':user_hash' => $user_hash
			)
		);

		$row_check_if_id_exists = $q_check_if_id_exists->fetchAll(PDO::FETCH_ASSOC);

		foreach ($row_check_if_id_exists AS $check_if_id_exists) {

			$id_count = $check_if_id_exists['id_count'];

		}






		// ************************************ { 2022-05-02 - RC } ************************************
		// IF THE ID IS UNIQUE, CONTINUE
		// *********************************************************************************************
		if ($id_count == 0) {
			$is_id_unique = true;
		}



	}
	while (!$is_id_unique);



	return $user_hash;



}




// ************************************ { 2022-05-02 - RC } ************************************
// GENERATE A PASSWORD HASH FROM THE USERNAME
// *********************************************************************************************
function get_password_hash($email_address, $password, &$errors) {



	// ************************************ { 2022-04-29 - RC } ************************************
	// MAKE SURE THE USERNAME AND PASSWORD ARE NOT EMPTY
	// *********************************************************************************************
	if ((strlen($email_address) == 0) || (strlen($password) == 0)) {
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






// ************************************ { 2022-05-02 - RC } ************************************
// LOG THE USER IN OR REDIRECT TO THE REGISTRATION PAGE AND SUPPLY AN ERROR MESSAGE
// *********************************************************************************************
function handle_login($errors = array()) {



	if (!empty($errors)) {

		$error_string = json_encode('<li>'.implode('<li>', $errors));

		header("Location: register.php?error=".$error_string);
		return;

	}


	header("Location: dashboard.php");
	return;


}