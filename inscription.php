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
if (empty($message_erreur)) {
  $Pseudo = "";
  $Mdp = "";
  $Nom = "";
  $Prenom = "";
  $Mail = "";
  $Mdp2 = "";
}

// Vérification d'une éventuelle connexion d'utilisateur
//
// Démmarrage d'une session si cela n'a pas déjà été fait
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if (isset($_SESSION['session_idutilisateur'])) {
  // Un utilisateur est connecté
  // -> récupération des variables de session dans des variables du script
  $session_idutilisateur = $_SESSION['session_idutilisateur'];
  $session_pseudo = $_SESSION['session_pseudo'];
  $session_nom = $_SESSION['session_nom'];
  $session_prenom = $_SESSION['session_prenom'];

  //*******************************************
  // Récupération des informations sur l'utilisateur dans la table utilisateur
  $requete = "select * from utilisateur where IdUtilisateur = $session_idutilisateur";
  // Exécution de la requête
  $resultat = mysqli_query($connexion, $requete);
  if ($resultat) {
    // Vérification du nombre de lignes du résultat
    if (mysqli_num_rows($resultat) != 0) {
      // Récupération de la ligne de la table correspondant
      // à l'utilisateur connecté

      $utilisateur = mysqli_fetch_assoc($resultat);
      // Initialisation des variables utilisées pour remplir la page d'inscription
      $Nom = $utilisateur['Nom'];
      $Prenom = $utilisateur['Prenom'];
      $Mail = $utilisateur['Mail'];
      $Pseudo = $utilisateur['Pseudo'];
      $Mdp = "";
      $Mdp2 = "";
    } else {
      // L'identifiant n'existe pas !
      $message_erreur .= "Utilisateur iconnu<br>\n";
    }
  } else {
    $message_erreur .= "Erreur de la requête <b>$requete</b><br>\n";
    $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
  }
}

if (isset($_POST['Soumettre'])) {
  //***************************
  // Clic sur le bouton "S'inscrire" de valeur name="inscrire"
  // Traitement du formulaire
  //
  // Filtrage du contenu de $_POST et assignation à des variables locales
  // htmlspecialchars() : Convertit les caractères spéciaux en entités HTML
  // trim() : Supprime les espaces (ou d'autres caractères) en début et fin de chaîne
  $Pseudo = trim(htmlspecialchars($_POST['Pseudo']));
  $Mdp = trim(htmlspecialchars($_POST['Mdp']));
  $Mail = trim(htmlspecialchars($_POST['Mail']));
  $Nom = trim(htmlspecialchars($_POST['Nom']));
  $Prenom = trim(htmlspecialchars($_POST['Prenom']));
  $Mdp = trim(htmlspecialchars($_POST['Mdp']));
  $Mdp2 = trim(htmlspecialchars($_POST['Mdp2']));

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

  if (empty($Nom)) {
    $message_erreur .= "Le champ nom est obligatoire<br>\n";
  } elseif (strlen($Nom) > 100) {
    $message_erreur .= "Le nom ne doit pas comporter plus de 100 caractères<br>\n";
  } elseif (!preg_match('/^([[:alpha:]]|-|[[:space:]]|\')*$/u', $Nom)) {
    // [[:alpha:]] : caractères alphabétique
    // [[:space:]] : espace blanc
    $message_erreur .= "Le nom ne doit comporter que des lettres<br>\n";
  }

  if (empty($Prenom)) {
    $message_erreur .= "Le champ prenom est obligatoire<br>\n";
  } elseif (strlen($Prenom) > 100) {
    $message_erreur .= "Le prénom ne doit pas comporter plus de 100 caractères<br>\n";
  } elseif (!preg_match('/^([[:alpha:]]|-|[[:space:]]|\')*$/u', $Prenom)) {
    $message_erreur .= "Le prénom ne doit comporter que des lettres<br>\n";
  }

  if (empty($Mail)) {
    $message_erreur .= "Le champ mail est obligatoire<br>\n";
  } elseif (strlen($Mail) > 250) {
    $message_erreur .= "Le champ mail doit être inférieur à 250 caractères<br>\n";
  } elseif (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', $Mail)) {
    $message_erreur .= "Le champ mail doit être valide mail@domaine.fr<br>\n";
  }

  if (empty($Mdp)) {
    $message_erreur .= "Veuillez renseigner votre mot de passe<br>\n";
  } elseif (strlen($Mdp) < 8) {
    $message_erreur .= "Le mot de passe doit contenir au moins 8 caractères<br>\n";
  } elseif (!preg_match('/^[[:graph:]]*$/u', $Mdp)) {
    // [[:graph:]] : tous les caractères imprimables sauf l'espace
    $message_erreur .= "Le mot de passe ne doit pas comporter d'espaces<br>\n";
  }

  if (strcmp($Mdp, $Mdp2) != 0) {
    $message_erreur .= "Les mots de passe sont différents<br>\n";
  }

 // Si aucun message d'erreur
 if (empty($message_erreur)) {
  //*******************************************
  // Saisie dans la table utilisateur
  // après vérification que le mail et le pseudo n'existent 
  // pas déjà dans la table
  //
  // Vérification que le mail n'existe pas déjà dans la table
  if (isset($session_idutilisateur)) {
    // Un utilisateur est connécté
    $requete = "select * from utilisateur where Mail = '$Mail' and IdUtilisateur != $session_idutilisateur";
  } else {
    // Aucun utilisateur connecté
    $requete = "select * from utilisateur where Mail = '$Mail'";
  }

  // Exécution de la requête
  $resultat = mysqli_query($connexion, $requete);
  if ($resultat) {
    // Vérification du nombre de lignes du résultat
    if (mysqli_num_rows($resultat) != 0) {
      // Le mail existe déjà dans la table
      $message_erreur .= "Erreur : le mail $Mail existe déjà<br>\n";
    }
  } else {
    $message_erreur .= "Erreur de la requête <b>$requete</b><br>\n";
    $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
  }

  // Vérification que le pseudo n'existe pas déjà dans la table
  if (isset($session_idutilisateur)) {
    // Un utilisateur est connécté
    $requete = "select * from utilisateur where Pseudo = '$Pseudo' and IdUtilisateur != $session_idutilisateur";
  } else {
    // Aucun utilisateur connecté
    $requete = "select * from utilisateur where Pseudo = '$Pseudo'";
  }
  // Exécution de la requête
  $resultat = mysqli_query($connexion, $requete);
  if ($resultat) {
    // Vérification du nombre de lignes du résultat
    if (mysqli_num_rows($resultat) != 0) {
      // Le pseudo existe déjà dans la table
      $message_erreur .= "Erreur : le pseudo $Pseudo existe déjà<br>\n";
    }
  } else {
    $message_erreur .= "Erreur de la requête <b>$requete</b><br>\n";
    $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
  }
}

// Si aucun message d'erreur
if (empty($message_erreur)) {
  // Chiffrement du mot de passe
  $passe_chiffre = password_hash($Mdp, PASSWORD_DEFAULT);

  if (isset($session_idutilisateur)) {
    // Un utilisateur est connecté
    // Requête de modification de l'utilisateur dans la table utilisateur
    $requete = "update utilisateur set Nom = '$Nom',
              Prenom = '$Prenom', Mail = '$Mail'";
    $requete .= ", Pseudo = '$Pseudo', Password = '$passe_chiffre'";
    $requete .= " where IdUtilisateur = $session_idutilisateur;";
  } else {
    // Aucun utilisateur connecté
    // Requête d'insertion de l'utilisateur dans la table utilisateur
    $requete = "insert into utilisateur (Nom, Prenom, Mail, Pseudo,
            Password) values
            ('$Nom', '$Prenom', '$Mail', ";
    $requete .= ", '$Pseudo', '$passe_chiffre'";
    $requete .= ");";
  }
  // Exécution de la requête 
  $resultat = mysqli_query($connexion, $requete);
  if (!$resultat) {
    $message_erreur .= "Erreur de la requête <b>$requete</b><br>\n";
    $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
  }
}

  // Si aucun message d'erreur
  if (empty($message_erreur)) {
    // Affiche un message de confirmation ainsi que les valeurs saisies
    if (isset($session_idutilisateur)) {
      // Un utilisateur est connécté
      $message .= "<p>Nous avons pris en compte votre modification.\n";
    } else {
      // Aucun utilisateur connecté
      $message .= "<p>Nous avons pris en compte votre inscription.\n";
    }
    $message .= "<br>Voici les données saisies :</p>\n";
    $message .= "<ul>\n";
    $message .= "<li>Nom : " . $Nom . "</li>\n";
    $message .= "<li>Prénom : " . $Prenom . "</li>\n";
    $message .= "<li>Mail : " . $Mail . "</li>\n";
    $message .= "<li>Pseudo : " . $Pseudo . "</li>\n";
    $message .= "<li>Mot de passe chiffré : " . $passe_chiffre . "</li>\n";
    $message .= "<li>Commentaire : ";
    $message .= "</ul>\n";
  }


  // Si aucun message d'erreur
  if (empty($message_erreur)) {
    // Affiche un message de confirmation ainsi que les valeurs saisies
    $message .= "<p>Vous êtes bien connectés en tant que :";
    $message .= $Pseudo;

  }
}

//Deconnexion de la base de données mymeteo
require "deconnexion_bd.php";
// S'il y a eu des erreurs ou si aucun appui sur les boutons "S'incrire" ou "Modifier"
if (!empty($message_erreur) || !(isset($_POST['inscrire']) || isset($_POST['modifier']))) {
  ?>
  <!-- **************************************** -->
  <!-- Affichage de la pas d'inscription        -->

<!DOCTYPE html>
<!-- **************************************** -->
<!-- Construction de la page HTML             -->
<html>

<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">
  <script src="login.js"></script>
  <link rel="stylesheet" href="register.css">

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
<?php
}
?>
</html>