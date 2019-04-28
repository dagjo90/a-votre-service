<?php
 require "config.php";
 session_start();
 $id = $_SESSION['id'];

 $user_model = new User();
 $user_model->updateUser($id, $_POST['pseudo'], $_POST['email'], $_POST['password']);

 header("Location: modifyProfile.php");
 die;
?>
