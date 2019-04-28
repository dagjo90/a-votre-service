<?php
require "config.php";
   $article_model = new Article();
   $articles = $article_model-> getAllArticles();
	 session_start();
if (isset($_SESSION['id'])) {
	 $user_model = new User();
	 $sessionUser = $user_model->getUser($_SESSION['id']);
	 echo "Welcome back <b>" .$sessionUser['pseudo'] . "</b> !
	 <br /> To log out click <a href=\"./controllers/logOut.php\">here</a>
	 <br /> To modify your profile click <a href=\"modifyProfile.php\">here</a>";
 } else {
	 echo "you're not connected, please register or connect <a href=\"register.php\">here</a";
 }

 if (isset($_SESSION['id']) && $sessionUser['isAdmin'] == 1) {
	 echo "<br><a href=\"articleForm.php\" ><button> add article </button> </a>";
 }
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

<header>
	<h1><a href="./index.php">À votre service</a></h1>
<div class="icones">
    <a href="https://www.instagram.com/"  class="fa fa-instagram" target="_blank"></a>
    <a href="https://www.youtube.com/" class="fa fa-youtube-play" target="_blank"></a>
    <a href="https://www.facebook.com" class="fa fa-facebook" target="_blank"></a>
    </div>
</header>

	<div class="main">

<?php foreach($articles as $article) {
  echo "
		<div class=\"article\">
		<div class=\"top\">
			<img class=\"img1\" src=\"./img/" . $article['photo1'] . "\" />
			<div class=\"right\">
				<h2>" . $article['titre'] . "</h2>
				<h5>" . $article['date'] . "</h5>
				<p class=\"accroche\"> "
          . $article['accroche'] . "
          </p>

					<a  href=\"./article.php?id=".$article['id'] . "\"><p class=\"more\"> Lire plus </p></a>
			</div>
		</div>

	</div>

"; } ?>


<footer>
		<div class="end">
<div class="icones3">
<a href="https://www.instagram.com/"  class="fa fa-instagram" target="_blank"></a>
<a href="https://www.youtube.com/" class="fa fa-youtube-play" target="_blank"></a>
<a href="https://www.facebook.com" class="fa fa-facebook" target="_blank"></a>

</div>

		<p>Rue Lonhienne **, 4000 Liège - info@plantinabox.be - 04 342 ** **</p>
				</div>
</footer>
</div>
</body>
</html>
