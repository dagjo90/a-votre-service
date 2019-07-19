<?php
require "../config.php";
$id= $_GET['id'];
$article = new Article();
$article->updateArticle($id, $_POST['photo1'], $_POST['titre'], $_POST['date'], $_POST['tags'], $_POST['accroche'], $_POST['texte1'], $_POST['photo2'], $_POST['photo3'], $_POST['texte2']);
header("Location: ../index.php");
die;
?>
