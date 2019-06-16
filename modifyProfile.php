
<?php
include "header.php";
$id = $_SESSION['id'];
$user_model = new User();
$sessionUser = $user_model->getUser($_SESSION['id']);
?>


<form class="registerForm" method="post" action="./controllers/updateUser.php">
  <input type="text" placeholder="Pseudo" name="pseudo" value="<?php echo  $sessionUser['pseudo']; ?>"/>
  <input type="text" placeholder="e-mail" name="email" value="<?php echo  $sessionUser['email']; ?>"/>
  <input type="password" placeholder="Enter new password" name="password"/>
  <input type="password" placeholder="Confirm new password" name="passwordConfirmation"/>
  <input type="submit" />

</form>
<?php
if (!$sessionUser['isAdmin']) {
echo " <a href=\"./controllers/deleteProfile.php\"><button>Delete Profile</button></a> ";
} else if ($sessionUser['isAdmin']) {
$user_model = new User();
$users = $user_model->getAllUsers();
foreach ($users as $user) {

if (!$user['isAdmin']) {

echo "<br><br> ".$user['pseudo'] . " <a href=\"./controllers/deleteProfile.php?id=" . $user['id'] . "\"><button>Delete Profile</button></a> <br>";
}}}

$comment_model = new Comment();
$comments = $comment_model->getAllCommentsFromUser($id);


foreach ($comments as $comment) {
$article_model = new Article();
$article = $article_model-> getArticle($comment['article_id']);


echo "<div class=\"comment\">
<a href=\"./controllers/deleteComment.php?id=".$comment['id']. "&article_id=". $comment['article_id'] . "\"><button > X </button> </a>
<a href=\"modifyComment.php?id=".$comment['id']. "&article_id=". $comment['article_id'] . "\"><button > modify </button> </a>

<h4 class=\"commentAuthor\">". $sessionUser['pseudo'] . "</h4>
<a href=\"./article.php?id=".$article['id'] . "\"><h4 class=\"commentAuthor\">". $article['titre'] . "</h4></a>
<p class=\"commentContent\">" .
$comment['content'] ."
</p>

</div>";

}

 ?>
 <?php
 include "footer.php";?>
