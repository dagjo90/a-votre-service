<?php
include "header.php";
?>


<?php
if (isset($_GET['mail'])) {?>

  <script>alert('message envoyé !');</script>
  <?php
}
$n = 1;
foreach($articles as $article) {

  if ($article['type'] == "aVotreService") {

    if ($n %2 ==0 ) {

      echo "<div class='color1'>";

    } else {
      echo "<div class='color2'>";
    }

  echo "
		<div class=\"article\">
    <div class='indexTitle'>
    <h2>" . $article['titre'] . "</h2>
    </div>
		<div class=\"top\">";

    if ($article['photo1'] !== "") {
			echo "<img class=\"img1\" src=\"./img/" . $article['photo1'] . "\" />";
    }

		echo	"<div class=\"right\">

				<p class=\"accroche\"> "
          . $article['accroche'] . "
          </p>

			</div>

		</div>
    <a  href=\"./article.php?id=".$article['id'] . "\"><p title=\"Afficher l'article\" class=\"more\"> Lire plus </p></a>

	</div>

";
echo "</div>";
$n++;
}


}

?>
<?php
include "footer.php";
?>
