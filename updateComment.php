<?php
require "config.php";
session_start();

$id = $_GET['id'];
$article_id = $_GET['article_id'];

$comment = new Comment();
$comment->modifyComment($id, $_POST['content']);

header("Location: article.php?id=".$article_id);
die;
?>
