<?php
require "config.php";
session_start();
$user_model = new User();
if (isset($_SESSION['id'])) {
$sessionUser = $user_model->getUser($_SESSION['id']);
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

<div class="main">

  <?php
  $id = $_GET['id'];

  $article_model = new Article();
  $article = $article_model-> getArticle($id);

  $comment_model = new Comment();
  $comments = $comment_model->getAllCommentsFromArticle($id);

  if (isset($_SESSION['id']) && $sessionUser['isAdmin'] == 1) {
    echo "<a href=\"./controllers/deleteArticle.php?id=".$article['id']. "\"><button> X </button> </a>";
    echo "<a href=\"modifyArticle.php?id=". $article['id'] . "\"><button> modify article</button> </a>";
  }

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
      </div>
    </div>

    <p class=\"texte1\">

    ". $article['texte1'] . "

    </p>

    <div class=\"images\">
      <img class=\"img1\" src=\"./img/" . $article['photo2'] . "\" />
      <img class=\"img1\" src=\"./img/" . $article['photo3'] . "\" />
    </div>
    <p class=\"texte2\">

    ". $article['texte2'] . "

      </p>

    <span class=\"signature\">

    ". $article['signature'] . "

    </span>
  </div>

  ";
  ?>

  <div class="comments">
    <h2> Commentaires : </h3>
  <?php
  foreach ($comments as $comment) {


    $user = $user_model-> getUser($comment['user_id']);


    if (isset($_SESSION['id']) && isset($_SESSION['id']) && $_SESSION['id'] === $user['id'] || isset($_SESSION['id']) && $sessionUser['isAdmin'] == 1){
      echo "<a href=\"./controllers/deleteComment.php?id=".$comment['id']. "&article_id=". $article['id'] . "\"><button > X </button> </a>";
      echo "<a href=\"modifyComment.php?id=".$comment['id']. "&article_id=". $article['id'] . "\"><button > modify </button> </a>";
    }

  echo "<div class=\"comment\">

  <h4 class=\"commentAuthor\">". $user['pseudo'] . "</h4>
  <p class=\"commentContent\">" .
  $comment['content'] ."
</p>

</div>";

  }
  ?>
</div>

<form class="commentForm" action="./controllers/submitComment.php" method="post">
  <textarea name="content"> </textarea>
  <input type="hidden" name="article_id" value ="<?php echo $article['id']; ?>"/>
  <input type="submit" />
</form>


</div>
</body>
</html>
