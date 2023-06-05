/*
//set of data
var dataset = ['A', 'B', 'C', 'D', 'E'];

//for each data from dataset, create a new section
d3.select('body')
.selectAll('p')
.data(dataset)
.enter()
.append('p')
.text('Paragraph');*/

function main() {
    /*var svg = d3.select("body")
        .append("svg")
        .attr("width", 300)
        .attr("height", 200)
        .style('background-color', 'lightgrey')*/
    // Sample data

    const data = [
        { label: 'A', value: 10 },
        { label: 'B', value: 20 },
        { label: 'C', value: 15 },
        { label: 'D', value: 25 },
    ];

    // Creating the SVG container
    const svg = d3.select(".affichage-graphs")
        .append('svg')
        .attr('width', 400)
        .attr('height', 300);

    // Creating the bars
    svg.selectAll('rect')
        .data(data)
        .enter()
        .append('rect')
        .attr('x', (d, i) => i * 60)
        .attr('y', (d) => 300 - d.value * 10)
        .attr('width', 50)
        .attr('height', (d) => d.value * 10)
        .attr('fill', 'steelblue');

    // Adding labels
    svg.selectAll('text')
        .data(data)
        .enter()
        .append('text')
        .text((d) => d.label)
        .attr('x', (d, i) => i * 60 + 20)
        .attr('y', (d) => 300 - d.value * 10 - 5)
        .attr('fill', 'white')
        .attr('text-anchor', 'middle');

}
