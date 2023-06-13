<?php
$message_erreur = "";
$message = "";

$connexion = mysqli_connect('localhost', 'root', '', 'mymeteo');
if (!$connexion) {
  $message_erreur .= "Erreur de connexion<br>\n";
  $message_erreur .= "Erreur n° " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "<br>\n";
} else {
  // Changement du jeu de caractères pour UTF8
  mysqli_set_charset($connexion, 'utf8');
}

$requete = "SELECT temperature, humidite, TIME(horodatage) as horodatage FROM donnees WHERE IdBalise = 1";
    // Exécution de la requête
$resultat = mysqli_query($connexion, $requete);
if ($resultat) {
        $message .= "données reçues<br>\n";
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
$dataTemperature = array();
$dataHumidite = array();
$dataHorodatage = array();

if ($resultat->num_rows > 0) {
    while ($row = $resultat->fetch_assoc()) {
        $dataTemperature[] = $row['temperature'];
        $dataHumidite[] = $row['humidite'];
        $dataHorodatage[] = $row['horodatage'];
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>MyMéteo</title>
    <link rel="stylesheet" href="indexIn.css">
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="indexln.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body >
    <header id="enGros">
        <h1>Ici vous pouvez interagir avec vos balises comme vous le souhaitez.</h1>
        <p id="bouton-acceuil"><button onclick="retourAcceuil()">Déconnexion<!--<img src="house-solid.svg">--></button></p>
    </header>
    <div class="flex-container">
        <div class="beacons-display">
            <div class="header-bdisplay">
                <p><h3>Vos balises :<br></h3></p>
            </div>
            <div class="beacons-list">
                <ol></ol>
                <button onclick="ajoutBalise()">Ajouter une balise</button>                
            </div>
            <p>Foutre une liste horizontale là et<br>en<br>dessous<br></p>
        </div>
        <div class="inside-class-container">
            <div class="filler-space">
                <p>Vous pouvez choisir quelles balises afficher, quelles données afficher, et vous pouvez superposer les données de différentes balises.<br></p>
            </div>
            <div class="affichage-graphs">
            <canvas id="myChart"></canvas>
      <script> // Données de température
        var dataTemperature = <?php echo json_encode($dataTemperature); ?>;
        // Données d'humidité
        var dataHumidite = <?php echo json_encode($dataHumidite); ?>;
        // Données d'horodatage
        var dataHorodatage = <?php echo json_encode($dataHorodatage); ?>;

        // Créer les ensembles de données pour la température et l'humidité
        var datasetTemperature = {
            label: 'Température',
            data: dataTemperature,
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false
        };

        var datasetHumidite = {
            label: 'Humidité',
            data: dataHumidite,
            borderColor: 'rgba(192, 75, 192, 1)',
            fill: false
        };

        // Créer le graphique avec Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dataHorodatage,
                datasets: [datasetTemperature, datasetHumidite]
            },
            options: {
                responsive: true,
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'second',
                            displayFormats: {
                                second: 'HH:mm:ss'
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Horodatage'
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Valeur'
                        }
                    }]
                }
            }
        });
    </script>
            </div>
        </div>
    </div>

</body>

</html>è