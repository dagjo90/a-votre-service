<?php include "header.php";?>

   <?php
   $id = $_GET['id'];
   $article_id = $_GET['article_id'];

   $comment_model = new Comment();
   $comment = $comment_model->getComment($id);

?>

<form class="modifyComment" method="POST" action="<?php echo "./controllers/updateComment.php?id=".$id."&article_id=". $article_id . "\""?>">
  <textarea name="content"><?php echo $comment['content']; ?> </textarea>
  <input type="submit" />
</form>


<?php include "footer.php";?>
