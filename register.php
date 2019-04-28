<?php
 require "config.php";
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


  <form class="loginForm" method="post" action="login.php">
    <input type="text" placeholder="e-mail" name="email"/>
    <input type="password" placeholder="password" name="password"/>
    <input type="submit" />
  </form>


<form class="registerForm" method="post" action="createUser.php">
  <input type="text" placeholder="Pseudo" name="pseudo"/>
  <input type="text" placeholder="e-mail" name="email"/>
  <input type="password" placeholder="password" name="password"/>
  <input type="password" placeholder="confirm password" name="passwordConfirmation"/>
  <input type="submit" />
</form>


</div>
</body>
</html>
