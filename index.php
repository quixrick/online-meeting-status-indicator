<?php

# Login form


print '
<html>

<head>

<title>Login Form</title>

</head>

<body>


<form action="login.php" method="post">

<table>
<tr>
  <td>Username:</td>
  <td><input type="text" name="username"></td>
</tr>
<tr>
  <td>Password:</td>
  <td><input type="password" name="password"></td>
</tr>
<tr>
  <td colspan="2" align="right"><input type="submit" name="submit" value="Log In">
</tr>
</table>

</form>


</body>

</html>';
