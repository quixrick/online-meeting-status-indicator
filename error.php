<?php

# ERROR PAGE
# /error.php


$error = '';
if ((isset($_GET['error'])) && (!empty($_GET['error']))) {
	$error = '<br><ul>'.json_decode($_GET['error']).'</ul><br>';
}

print $error;