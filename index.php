<?php

# MAIN PAGE
# /index.php


$error = '';
if ((isset($_GET['error'])) && (!empty($_GET['error']))) {
	$error = '<br><ul>'.json_decode($_GET['error']).'</ul><br>';
}



print '
<html>

<head>

<title>Login Form</title>

</head>

<body>

'.$error.'

<form action="login.php" method="post">

<table>
<tr>
  <td>Email Address:</td>
  <td><input type="text" name="email_address"></td>
</tr>
<tr>
  <td>Password:</td>
  <td><input type="password" name="password"></td>
</tr>
<tr>
  <td colspan="2" align="right"><input type="submit" name="submit" value="Log In">
</tr>
<tr>
  <td colspam="2" align="center"><a href="register.php">Create an account</a></td>
</tr>
</table>

</form>


</body>

</html>';
