<?php

// REGISTER
// /register.php






// ************************************ { 2022-05-02 - RC } ************************************
// LOAD THE CONFIG FILE AND CONNECT TO THE DATABASE
// *********************************************************************************************
require_once 'config.php';

$conn = new PDO("mysql:host=".$settings['db_host'].";dbname=".$settings['db_name'].";",$settings['db_user'],$settings['db_pass']);






// ************************************ { 2022-05-02 - RC } ************************************
// CHECK TO SEE IF WE HAVE ERRORS
// *********************************************************************************************
$error = '';
if ((isset($_GET['error'])) && (!empty($_GET['error']))) {
	$error = '<br><ul>'.json_decode($_GET['error']).'</ul><br>';
}






// ************************************ { 2022-05-02 - RC } ************************************
// DISPLAY THE REGISTRATION FORM
// *********************************************************************************************
print '
<html>

<head>

<title>Registration Form</title>

</head>

<body>

'.$error.'

<form action="register_handler.php" method="post">

<table>
<tr>
  <td>First Name:</td>
  <td><input type="text" name="first_name" style="width: 150px;"></td>
  <td>Last Name:</td>
  <td><input type="text" name="last_name" style="width: 150px;"></td>
</tr>
<tr>
  <td>Email Address:</td>
  <td colspan="3"><input type="text" name="email_address" style="width: 400px;"></td>
</tr>
<tr>
  <td>Password:</td>
  <td colspan="3"><input type="password" name="password" style="width: 400px;"></td>
</tr>
<tr>
  <td colspan="4" align="center"><a href="privacy.php" target="_blank">Read our super short privacy policy here</a></td>
</tr>
<tr>
  <td colspan="4" align="right"><input type="submit" name="submit" value="Create Account">
</tr>
</table>

</form>


</body>

</html>';