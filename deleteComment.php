<?php
require "config.php";

$id = $_GET['id'];
$article_id= $_GET['article_id'];

$comment = new Comment();
$comment->deleteComment($id, $_SESSION['id']);
header("Location: article.php?id=".$article_id);
die;
?>
