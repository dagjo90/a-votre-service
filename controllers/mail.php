<?php
$name = $_POST['nom'];
$email = $_POST['email'];
$message = $_POST['contenuMail'];
$formcontent="Message de : $name \n\n $message";
$recipient = "info@madameprudence.be";
$subject = $_POST['sujet'];;
$mailheader = "From: $email \r\n";
mail($recipient, $subject, $formcontent, $mailheader) or die("Error!");
header("Location: ../index.php?mail=ok");
die;
?>
