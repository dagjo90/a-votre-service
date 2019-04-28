<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="style.css" type="text/css" rel="stylesheet">
	<title>Blog</title>
	<link href="https://fonts.googleapis.com/css?family=Amatic+SC|Indie+Flower" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link href="https://fonts.googleapis.com/css?family=Cabin+Sketch|Comfortaa|Homemade+Apple|Marck+Script|Open+Sans+Condensed:300" rel="stylesheet">

</head>
<body>

<header>
	<h1><a href="index.php">Mon Blog</a></h1>
<div class="icones">
    <a href="https://www.instagram.com/"  class="fa fa-instagram" target="_blank"></a>
    <a href="https://www.youtube.com/" class="fa fa-youtube-play" target="_blank"></a>
    <a href="https://www.facebook.com" class="fa fa-facebook" target="_blank"></a>
    </div>
</header>

<div class="main">
  <form method="post" class="articleForm" action="submitArticle.php">
    <input class="titreInput" type="text" name ="titre" placeholder="Titre"/>
    <input class="photoInput" type="text" name="photo1" placeholder="Photo 1"/>
    <input class="tagsInput" type="text" name="tags" placeholder="Tags"/>
    <textarea class="resumeInput" type="text" name="accroche" placeholder="accroche"/></textarea>
    <textarea class="textInput" type="text" name="texte1" placeholder="texte 1"/></textarea>
    <input class="photoInput" type="text" name="photo2" placeholder="Photo 2"/>
    <input class="photoInput" type="text" name="photo3" placeholder="Photo 3"/>
    <textarea class="textInput" type="text" name="texte2" placeholder="Texte 2"/></textarea>
    <input class="submitArticle" type="submit"/>


  </form>
</div>

</body>
</html>
