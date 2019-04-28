<?php
require "config.php";

$id = $_GET['id'];


$comment = new Comment();
$comment->deleteAllCommentsFromArticle($id);

$article = new Article();
$article->deleteArticle($id);


header("Location: index.php");
die;
?>
