<?php
 require "config.php";
 session_start();
 $id = $_SESSION['id'];
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


<form class="registerForm" method="post" action="updateUser.php">
  <input type="text" placeholder="Pseudo" name="pseudo" value="<?php echo  $sessionUser['pseudo']; ?>"/>
  <input type="text" placeholder="e-mail" name="email" value="<?php echo  $sessionUser['email']; ?>"/>
  <input type="password" placeholder="Enter new password" name="password"/>
  <input type="password" placeholder="Confirm new password" name="passwordConfirmation"/>
  <input type="submit" />

</form>
<?php
if (!$sessionUser['isAdmin']) {
echo " <a href=\"deleteProfile.php\"><button>Delete Profile</button></a> ";
} else if ($sessionUser['isAdmin']) {
$user_model = new User();
$users = $user_model->getAllUsers();
foreach ($users as $user) {

if (!$user['isAdmin']) {

echo "<br><br> ".$user['pseudo'] . " <a href=\"deleteProfile.php?id=" . $user['id'] . "\"><button>Delete Profile</button></a> <br>";
}}}

$comment_model = new Comment();
$comments = $comment_model->getAllCommentsFromUser($id);


foreach ($comments as $comment) {
$article_model = new Article();
$article = $article_model-> getArticle($comment['article_id']);


echo "<div class=\"comment\">
<a href=\"deleteComment.php?id=".$comment['id']. "&article_id=". $comment['article_id'] . "\"><button > X </button> </a>
<a href=\"modifyComment.php?id=".$comment['id']. "&article_id=". $comment['article_id'] . "\"><button > modify </button> </a>

<h4 class=\"commentAuthor\">". $sessionUser['pseudo'] . "</h4>
<a href=\"article.php?id=".$article['id'] . "\"><h4 class=\"commentAuthor\">". $article['titre'] . "</h4></a>
<p class=\"commentContent\">" .
$comment['content'] ."
</p>

</div>";

}

 ?>
</div>
</body>
</html>
