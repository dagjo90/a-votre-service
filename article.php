
  <?php
  include "header.php";
  $id = $_GET['id'];

  $article_model = new Article();
  $article = $article_model-> getArticle($id);


  if (isset($_SESSION['id']) && $sessionUser['isAdmin'] == 1) {
    echo "<div class=\"adminArticle\">";

    echo "<a class=\"admin\" href=\"./controllers/deleteArticle.php?id=".$article['id']. "\">Supprimer l'article | </a>";
    echo "<a class=\"admin\" href=\"modifyArticle.php?id=". $article['id'] . "\">Modifier l'article</a>";
    echo "</div>";
  }

  echo "
  <div class=\"color2\">


    <div class=\"article\">
    <div class=\"top\">";

    if ($article['photo1'] !== "") {
      echo "<img class=\"img1\" src=\"./img/" . $article['photo1'] . "\" />";
      }
      echo "<div class=\"right\">
        <h2>" . $article['titre'] . "</h2>
        <p class=\"accroche\"> "
          . $article['accroche'] . "
          </p>
      </div>
    </div>

    <p class=\"texte1\">

    ". $article['texte1'] . "

    </p>";

    if ($article['photo2'] !== "" || $article['photo3'] !== ""){

  echo  "<div class=\"images\">";

  if ($article['photo2'] !== "") {
    echo "  <img class=\"img1\" src=\"./img/" . $article['photo2'] . "\" />";
  }if ($article['photo3'] !== "") {
      echo "<img class=\"img1\" src=\"./img/" . $article['photo3'] . "\" />
    </div>";

  }}

    echo "
    <p class=\"texte2\">

    ". $article['texte2'] . "

      </p>

    <span class=\"signature\">

    ". $article['signature'] . "

    </span>
  ";?>

<div class="socialButtons">
  <div class="fb-like" data-href="http://madameprudence.be/article.php?id=<?php echo $id ?>" data-width="" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="false"></div>
  <div class="fb-share-button" data-href="http://madameprudence.be/article.php?id=<?php echo $id ?>" data-layout="button_count" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fmadameprudence.be%2Farticle.php%3Fid%3D<?php echo $id?>&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Partager</a></div>
</div>
</div>
</div>


<div class="color1">
  <div class="comments">
    <h2> Commentaires : </h3>

      <div class="fb-comments" data-href="http://madameprudence.be/article.php?id=<?php echo $id?>" data-width="350px" data-numposts="10"></div>
</div>
</div>
<?php
include "footer.php";
?>
