<?php
require "config.php";
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
 	<link href="https://fonts.googleapis.com/css?family=Amatic+SC|Indie+Flower" rel="stylesheet">
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

 <link href="https://fonts.googleapis.com/css?family=Cabin+Sketch|Comfortaa|Homemade+Apple|Marck+Script|Open+Sans+Condensed:300" rel="stylesheet">
 </head>
 <body>

 <div class="main">

   <?php
   $id = $_GET['id'];
   $article_id = $_GET['article_id'];

   $comment_model = new Comment();
   $comment = $comment_model->getComment($id);

?>

<form class="modifyComment" method="POST" action="<?php echo "updateComment.php?id=".$id."&article_id=". $article_id . "\""?>">
  <textarea name="content"><?php echo $comment['content']; ?> </textarea>
  <input type="submit" />
</form>
 </div>
</body>
</html>
