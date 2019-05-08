<?php
require "../config.php";

$article = new Article();
$article->createArticle($_POST['photo1'], $_POST['titre'], $_POST['tags'], $_POST['accroche'], $_POST['texte1'], $_POST['photo2'], $_POST['photo3'], $_POST['texte2'], $_POST['type']);


header("Location: ../index.php");
die;
?>
