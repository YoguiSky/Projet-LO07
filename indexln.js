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


var svg = d3.select("body")
            .append("svg")
            .attr("width", 300)
            .attr("height", 200)
            .style('background-color', 'lightgrey')
