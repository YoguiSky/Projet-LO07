<?php
// **********************************************
// Traitement du formulaire
//
// La variable $message contiendra les éventuels messages de l'application à afficher
$message = "";

// La variable $message_erreur contiendra les éventuels messages d'erreur de l'application à afficher
$message_erreur = "";

//COnnexion à la base de données mymeteo

require "connexion_bd.php";

// Initialisation des variables contenant les données saisies dans le formulaire
// et utilisées pour remplir le formulaire
if empty(message_erreur){
$Pseudo = "";
$Mdp = "";
$Nom="";
$Prenom="";
$Mail="";
$Mdp2 ="";
}
if (isset($_POST['connexion'])) {
  //***************************
  // Clic sur le bouton "S'inscrire" de valeur name="inscrire"
  // Traitement du formulaire
  //
  // Filtrage du contenu de $_POST et assignation à des variables locales
  // htmlspecialchars() : Convertit les caractères spéciaux en entités HTML
  // trim() : Supprime les espaces (ou d'autres caractères) en début et fin de chaîne
  $Pseudo = trim(htmlspecialchars($_POST['Pseudo']));
  $Mdp = trim(htmlspecialchars($_POST['Mdp']));
  
  // Vérification de toutes les valeurs saisies

  if (empty($Pseudo)) {
    $message_erreur .= "Veuillez renseigner votre pseudo<br>\n";
  } elseif (strlen($Pseudo) > 50) {
    $message_erreur .= "Le pseudo ne doit pas comporter plus de 50 caractères<br>\n";
  } elseif (!preg_match('/^([[:alpha:]]|-|[[:space:]]|\')*$/u', $Pseudo)) {
    // [[:alpha:]] : caractères alphabétique
    // [[:space:]] : espace blanc
    $message_erreur .= "Le pseudo ne doit comporter que des lettres<br>\n";
  }
  if (empty($Mdp)) {
    $message_erreur .= "Veuillez renseigner votre mot de passe<br>\n";
  }

  

  // Si aucun message d'erreur
  if (empty($message_erreur)) {
    // Affiche un message de confirmation ainsi que les valeurs saisies
    $message .= "<p>Vous êtes bien connectés en tant que :";
    $message .= Pseudo;
    
  }
}

//Deconnexion de la base de données mymeteo
require "deconnexion_bd.php";
?>
<!DOCTYPE html>
<!-- **************************************** -->
<!-- Construction de la page HTML             -->
<html>
  <head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
  </head>
  <body>
    <div>
      <?php
      if (!empty($message_erreur) || !empty($message)) {
        ?>
        <!-- **************************************** -->
        <!-- Messages éventuels de l'application      -->
        <div>
          <h1> Logs </h1>
          <div id="logs">
            <?php
            if (!empty($message_erreur)) {
              echo '<div>' . $message_erreur . "</div>\n";
            }
            if (!empty($message)) {
              echo '<div>' . $message . "</div>\n";
            }
            ?>
          </div>                
        </div>          
        <?php
      }
      ?>
    </div>
  </body>
</html>