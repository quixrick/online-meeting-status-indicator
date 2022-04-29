<?php

// LOGIN
// /login.php


$username = $_POST['username'];
$password = $_POST['password'];


print '<br>Username: '.$username;
print '<br>Password: '.$password;




// echo password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));


$compare_pass = '$2y$12$yoyDkINhr1cIGUrC0ltbq.FHj2rHU/pJSWNMPTqi5JI2nnWwMKMfu';




if (password_verify($password, $compare_pass)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}

