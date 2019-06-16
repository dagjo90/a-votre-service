<?php
include "header.php";
?>



<?php
$n = 1;
foreach($articles as $article) {

  if ($article['type'] == "humeur") {

    if ($n %2 ==0 ) {

      echo "<div class='color1'>";

    } else {
      echo "<div class='color2'>";
    }

  echo "
		<div class=\"article\">
		<div class=\"top\">";
    if ($article['photo1'] !== "") {
      echo "<img class=\"img1\" src=\"./img/" . $article['photo1'] . "\" />";
    }
		echo "	<div class=\"right\">
				<h2>" . $article['titre'] . "</h2>
				<h5>" . $article['date'] . "</h5>
				<p class=\"accroche\"> "
          . $article['accroche'] . "
          </p>

					<a  href=\"./article.php?id=".$article['id'] . "\"><p class=\"more\"> Lire plus </p></a>
			</div>
		</div>

	</div>";

  echo "</div>";
  $n++;

}


}

?>

<?php
include "footer.php";
?>
