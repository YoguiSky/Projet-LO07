<?php
// Connexion à la base de données cuicui
$connexion = mysqli_connect('localhost', 'root', '', 'mymeteo');
if (!$connexion) {
  $message_erreur .= "Erreur de connexion<br>\n";
  $message_erreur .= "Erreur n° " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "<br>\n";
} else {
  // Changement du jeu de caractères pour UTF8
  mysqli_set_charset($connexion, 'utf8');
}
?>