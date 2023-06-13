<?php
// Vérifier si des données JSON ont été reçues
$jsonData = $_POST["jsonData"];
var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jsonData'])) {
    $jsonData = $_POST['jsonData'];

    echo '<h1>data</h1>';
    var_dump($jsonData);

    $message_erreur = "";
    $message = "";

    $data = json_decode($jsonData, true);
    //var_dump($data);

    $connexion = mysqli_connect('localhost', 'root', '', 'mymeteo');
    if (!$connexion) {
        $message_erreur .= "Erreur de connexion<br>\n";
        $message_erreur .= "Erreur n° " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "<br>\n";
    } else {
        // Changement du jeu de caractères pour UTF8
        mysqli_set_charset($connexion, 'utf8');
    }

    $requete = 'INSERT INTO donnees (IdBalise, temperature, humidite, horodatage) VALUES ('.$data["IdBalise"].','.$data["temperature"].','.$data["humidite"].', current_timestamp())';
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
        $message .= "Message envoyé<br>\n";
    } else {
        $message_erreur .= "Erreur de la requête <b>$requete</b><br>\n";
        $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
    }

    if ($connexion) {
        $deconnexion_reussie = mysqli_close($connexion);
        if (!$deconnexion_reussie) {
            $message_erreur .= "Erreur de déconnexion<br>\n";
        }
    }

    echo 'Erreur : ' . $message_erreur . '<br>';
    echo 'Message : ' . $message . '<br>';
} else {
    echo 'Aucune donnée JSON reçue';
}
?>
