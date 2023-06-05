// Convention D3 pour gérer la marge du graphique
var margin = { top: 20, right: 20, bottom: 30, left: 40 },
    width = 600 - margin.left - margin.right,
    height = 400 - margin.top - margin.bottom;

// D3 nous propose des outils pour gérer les échelles de valeurs de nos données.
// Ici, nos données seront réparties sur un intervalle continu.
// Nous savons que nos données d'entrées sont comprises entre 0 et 100.
// En sortie, nous les étalerons sur l'axe des abscisses soit la largeur
// du graphe.
var x = d3.scale.linear()
    .domain([0, 100]) //domaine d'entrée
    .range([0, width]); // domaine de sortie

// Même chose pour l'axe des ordonnées.
// Notez qu'en SVG, la coordonnée (0, 0) se trouve en haut à gauche    

var y = d3.scale.linear()
    .range([0, height])
    .domain([0, 100]);

// Nous créons l'élément SVG nécessaire au rendu du graphique.
// Et hop ! direct dans le DOM.
var chart = d3.select('#chart').append('svg')
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

// Que serait un graphique sans axes ?
// L'api est généreuse et contient tout ce qu'il faut
// pour produire un rendu élégant
var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");
chart.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis);

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");
chart.append("g")
    .attr("class", "y axis")
    .call(yAxis);

/*
 * Notre fonction de génération de données aléatoires
 * Retourne un résultat de la forme suivante :
 * [
 *     { x: 10, y: 55, r: 5},
 *     { x: 87, y: 42, r: 2},
 *     …
 * ]
 */

function getData() {
    var _randomData = function() {
        return {
            x: Math.floor(Math.random() * 100),
            y: Math.floor(Math.random() * 100),
            r: Math.floor(Math.random() * 10),
        };
    };
    var nbData = Math.floor(Math.random() * 8) + 3;
    return d3.range(nbData).map(_randomData);
};

/*
 * C'est ici que nous allons afficher nos données sous
 * forme de cercles chamarés et chatoyants.
 *
 * Le paramètre data est bien évidemment un tableau d'objets
 */

function redraw(data) {

    // Créé la sélection initiale, et bind les données
    var circles = chart.selectAll('circle')
        .attr("class", "chart")
        .data(data);

    // Lorsque l'on créé les nœuds, on les place directement au coordonnées
    // correctes, mais avec un rayon de 0, ce qui permettra une
    // animation des plus primesautières.
    circles.enter().append('circle')
        .attr("cx", function(d) { return x(d.x); })
        .attr("cy", function(d) { return height - y(d.y); })
        .attr("r", 0);

    // Idem, lorsqu'une donnée n'existe plus, on fait disparaître le
    // cercle correspondant en réduisant élégamment son rayon à 0
    circles.exit()
        .transition()
        .duration(750)
        .attr("r", 0)
        .remove();

    // Voici maintenant le traitement effectués sur les nœuds liés à
    // des données existantes. Notez que les nœuds de la sélection `enter`
    // seront également concernés ici.
    circles
        .attr("fill", function(d, i) { return color(i); })
        .transition()
        .duration(750)
        .attr("cx", function(d) { return x(d.x); })
        .attr("cy", function(d) { return height - y(d.y); })
        .attr("r", function(d) { return r(d.r); });
};

var updateButton = document.getElementById('update-data');
updateButton.addEventListener('click', function() {
    data = getData();
    redraw(data);
});
data = getData();
redraw(data);