<?php
include "header.php";
?>

<form method="post" action="./controllers/mail.php"class="contactForm">
  <h2 id="loginHeader">Me Contacter</h2>
  <input name="nom" type="text" placeholder="Votre nom" />
  <input name="email" type="text" placeholder="Votre email" />
  <input name="sujet" type="text" placeholder="Sujet" />
  <textarea name="contenuMail" placeholder="Message"></textarea>
  <div class="contactfooter">

  <input class="contactButton success" type="submit" value="Envoyer"/> <input type="reset" class="contactButton danger" value="Effacer">
</div></form>

<?php
include "footer.php";
?>
