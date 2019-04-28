<?php
require "../config.php";
session_start();

$comment = new Comment();
$comment->createComment($_POST['article_id'], $_SESSION['id'], $_POST['content']);
header("Location: ../article.php?id=".$_POST['article_id']);
die;

?>
