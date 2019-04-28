<?php
require "../config.php";
session_start();
$comment = new Comment();
$user_model = new User();

if (isset($_GET['id'])) {

  $id = $_GET['id'];
  $comment->deleteAllCommentsFromUser($id);
  $user_model->deleteUser($id);

} else {
  $id = $_SESSION['id'];
  $comment->deleteAllCommentsFromUser($id);

  $user_model->deleteUser($id);
  unset($_SESSION['id']);


}

header("Location: ../index.php");
die;
?>
