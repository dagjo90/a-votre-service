<?php
require 'config.php';
$usermodel = new User();
$psw= htmlspecialchars($_POST['password']);
$email=htmlspecialchars($_POST['email']);
$usermodel->loginUser($psw, $email);
header("Location: index.php");
die;
?>
