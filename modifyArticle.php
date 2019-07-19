
<?php
include "header.php";
if (!isset($_SESSION['id'])) {
  header('Location: index.php');
}
?>

  <?php
  // get article to modify by id
  $id = $_GET['id'];
  $article_model = new Article();
  $article = $article_model-> getArticle($id);
?>

  <form method="post" class="articleForm" action="<?php echo "./controllers/updateArticle.php?id=".$id?>" >
    <input class="titreInput" type="text" name ="titre" placeholder="Titre" value="<?php echo $article['titre'];?>"/>
    <input class="photoInput" type="text" name="photo1" placeholder="Photo 1" value="<?php echo $article['photo1'];?>"/>
    <input type="date" name="date"/>
    <input class="tagsInput" type="text" name="tags" placeholder="Tags" value="<?php echo $article['tags'];?>"/>
    <textarea class="resumeInput" type="text" name="accroche" placeholder="accroche"/> <?php echo $article['accroche'];?></textarea>
    <textarea class="textInput" type="text" name="texte1" placeholder="texte 1"/> <?php echo $article['texte1'];?></textarea>
    <input class="photoInput" type="text" name="photo2" placeholder="Photo 2" value="<?php echo $article['photo2'];?>"/>
    <input class="photoInput" type="text" name="photo3" placeholder="Photo 3" value="<?php echo $article['photo3'];?>"/>
    <textarea class="textInput" type="text" name="texte2" placeholder="Texte 2"/> <?php echo $article['texte2'];?></textarea>
    <input class="submitArticle" type="submit"/>
  </form>

	<?php include "footer.php"?>
