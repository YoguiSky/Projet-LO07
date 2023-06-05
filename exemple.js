/*alert("Hello!");
<h1>Bonjour, ici on teste d3</h1>
<p>Test en cours...</p>
*/

function main(){
    //d3 jte met là
    //d3.selectAll(".myClass").style("color", "pink")
    d3.select("tr").selectAll("td").style("background-color", "magenta")
    d3.select("body").append("p").text("Je suis un paragraphe ajouté.")
}