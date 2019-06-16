<?php
include "header.php";
?>


  <form class="loginForm" method="post" action="./controllers/login.php">
    <h2 id="loginHeader">Se connecter</h2>
    <input type="text" placeholder="e-mail" name="email"/>
    <input type="password" placeholder="password" name="password"/>
    <input id="submitLogin" type="submit" value="Connexion" />
  </form>


<!--<form class="registerForm" method="post" action="./controllers/createUser.php">
  <input type="text" placeholder="Pseudo" name="pseudo"/>
  <input type="text" placeholder="e-mail" name="email"/>
  <input type="password" placeholder="password" name="password"/>
  <input type="password" placeholder="confirm password" name="passwordConfirmation"/>
  <input  type="submit" />
</form>-->

<?php
include "footer.php";
?>
