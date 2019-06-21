<?php
require "config.php";
   $article_model = new Article();
   $articles = $article_model-> getAllArticles();
	 session_start();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="style.css" type="text/css" rel="stylesheet">
	<title>À votre service</title>

  <link href="https://fonts.googleapis.com/css?family=News+Cycle&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Amatic+SC" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Cabin+Sketch|Comfortaa|Homemade+Apple|Marck+Script|Open+Sans+Condensed:300" rel="stylesheet">

</head>
<body>
<div id="nav" class="nav">
  <button id="closeMenu" title="Fermer le menu">X</button>

<div class ="navContainer">


<a href="index.php"><h3 title="Afficher les billets d'humeur">A votre service</h3></a>

<ul class="navHumeur">

  <?php foreach($articles as $article) {
    if ($article['type'] == "aVotreService") {
      echo "<a href=\"./article.php?id=".$article['id']."\"><li>".$article['titre']."</li></a>";
  }
  }

  ?>

  </ul>


  <h3 title="Afficher les billets d'humeur"><a href="humeur.php">Billets d'humeur</a></h3>
  <ul class="navHumeur">
    <?php foreach($articles as $article) {
      if ($article['type'] == "humeur") {
        echo "<a href=\"./article.php?id=".$article['id']."\"><li>".$article['titre']."</li></a>";
    }
  }
  ?>
</ul>
<a href="" title="Afficher la page de présentation"><h3>À propos de moi</h3></a>
<a href="" title="Afficher la page de contact"><h3>Contact</h3></a>
<a href="./register.php" title="Se connecter en tant qu'administrateur"><h4>Mode Administrateur</h4></a>
</div>

</div>

<div class="splashinfos">
  <div class="filter">
    <header>
      <?php
      if (isset($_SESSION['id'])) {
         $user_model = new User();
         $sessionUser = $user_model->getUser($_SESSION['id']);
         echo "<div class=\"adminMenu\">";


         echo "<span >" .$sessionUser['pseudo'] . " | </span>
         <a href=\"./controllers/logOut.php\" class=\"openMenu admin\">Déconnexion | </a>";

       }

       if (isset($_SESSION['id']) && $sessionUser['isAdmin'] == 1) {
         echo "<a href=\"articleForm.php\" class=\"openMenu admin\">Ajouter un article</a>";
         echo "</div>";
       }

      ?>
    	<h1><a href="./index.php" title="Retourner à la page d'accueil">À votre service</a></h1>
    <div class="icones">
        <a href="https://www.instagram.com/"  title="Afficher le compte Instagram"class="fa fa-instagram" target="_blank"></a>
        <a href="https://www.youtube.com/" title="Afficher la chaine Youtube" class="fa fa-youtube-play" target="_blank"></a>
        <a href="https://www.facebook.com" title="Afficher la page Facebook" class="fa fa-facebook" target="_blank"></a>
        <button id="buttonMenu" title="Afficher le menu">Menu</button>

        </div>
    </header>
  <p>
TEST  </p>

  <a  href="#go" class="headerMore"><p> Allons-y !</p></a>

  </div>
</div>

	<div class="main" id="go">
