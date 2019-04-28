<?php
require "../config.php";


try {
  //create user
$user = new User();
$user->createUser($_POST['pseudo'], $_POST['email'], $_POST['password']);

// then login
 session_start();
 $psw= htmlspecialchars($_POST['password']);
 $email=htmlspecialchars($_POST['email']);
 $user->loginUser($psw, $email);
} catch ( \PDOException $e ) {
    return $e -> getMessage();
}


header("Location: ../index.php");
die;
?>
