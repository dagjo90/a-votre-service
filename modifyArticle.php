<?php
require "config.php";
session_start();
$user_model = new User();
$sessionUser = $user_model->getUser($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="style.css" type="text/css" rel="stylesheet">
	<title>À votre service</title>
	<link href="https://fonts.googleapis.com/css?family=Amatic+SC|Indie+Flower" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link href="https://fonts.googleapis.com/css?family=Cabin+Sketch|Comfortaa|Homemade+Apple|Marck+Script|Open+Sans+Condensed:300" rel="stylesheet">
</head>
<body>

<div class="main">

  <?php
  // get article to modify by id
  $id = $_GET['id'];
  $article_model = new Article();
  $article = $article_model-> getArticle($id);
?>

  <form method="post" class="articleForm" action="<?php echo "updateArticle.php?id=".$id?>" >
    <input class="titreInput" type="text" name ="titre" placeholder="Titre" value="<?php echo $article['titre'];?>"/>
    <input class="photoInput" type="text" name="photo1" placeholder="Photo 1" value="<?php echo $article['photo1'];?>"/>
    <input class="tagsInput" type="text" name="tags" placeholder="Tags" value="<?php echo $article['tags'];?>"/>
    <textarea class="resumeInput" type="text" name="accroche" placeholder="accroche"/> <?php echo $article['accroche'];?></textarea>
    <textarea class="textInput" type="text" name="texte1" placeholder="texte 1"/> <?php echo $article['texte1'];?></textarea>
    <input class="photoInput" type="text" name="photo2" placeholder="Photo 2" value="<?php echo $article['photo2'];?>"/>
    <input class="photoInput" type="text" name="photo3" placeholder="Photo 3" value="<?php echo $article['photo3'];?>"/>
    <textarea class="textInput" type="text" name="texte2" placeholder="Texte 2"/> <?php echo $article['texte2'];?></textarea>
    <input class="submitArticle" type="submit"/>
  </form>
</div>

</body>
</html>
