	<?php include "header.php";

	if (!isset($_SESSION['id'])) {
		header('Location: index.php');
	}


	?>
  <form method="post" class="articleForm" action="./controllers/submitArticle.php">
    <input class="titreInput" type="text" name ="titre" placeholder="Titre"/>
    <input class="photoInput" type="text" name="photo1" placeholder="Photo 1"/>
		<input type="date" name="date"/>
    <input class="tagsInput" type="text" name="tags" placeholder="Tags"/>
    <textarea class="resumeInput" type="text" name="accroche" placeholder="accroche"/></textarea>
    <textarea class="textInput" type="text" name="texte1" placeholder="texte 1"/></textarea>
    <input class="photoInput" type="text" name="photo2" placeholder="Photo 2"/>
    <input class="photoInput" type="text" name="photo3" placeholder="Photo 3"/>
    <textarea class="textInput" type="text" name="texte2" placeholder="Texte 2"/></textarea>
<br />
		<select name="type">
			<option value = ""> Type d'article </option>
			<option value = "aVotreService"> A votre service </option>
			<option value = "humeur"> Billet d'humeur </option>
		</select>
		<br />
    <input class="submitArticle" type="submit"/>


  </form>
	<?php include "footer.php"?>
