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
	<title>Madame Prudence</title>

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
<a href="./contact.php" title="Afficher la page de contact"><h3>Contact</h3></a>
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
         echo "<a href=\"articleForm.php\" class=\"openMenu admin\">Ajouter un article</a>";
         echo "</div>";
       }
      ?>
    	<h1><a href="./index.php" title="Retourner à la page d'accueil">Madame Prudence</a></h1>
        <button id="buttonMenu" title="Afficher le menu">Menu</button>
    </header>
  <p>Vous aimez les centres d'appels surchargés, les musiques d'attente interminables et les standardistes écervelées ?<br /> Vous allez m'adorer !</p>
  </div>
</div>

	<div class="main" id="go">
