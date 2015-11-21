var data = [{
    "sale": "202",
    "year": "2000"
}, {
    "sale": "225",
    "year": "2002"
}, {
    "sale": "179",
    "year": "2004"
}, {
    "sale": "199",
    "year": "2006"
}, {
    "sale": "102",
    "year": "2008"
}, {
    "sale": "176",
    "year": "2010"
}];

/*
var data2 = [{
    "sale": "152",
    "year": "2000"
}, {
    "sale": "189",
    "year": "2002"
}, {
    "sale": "179",
    "year": "2004"
}, {
    "sale": "199",
    "year": "2006"
}, {
    "sale": "134",
    "year": "2008"
}, {
    "sale": "176",
    "year": "2010"
}];
*/
var vis = d3.select("#visualization"),
    WIDTH = 1000,
    HEIGHT = 500,
    MARGINS = {
        top: 50,
        right: 20,
        bottom: 50,
        left: 100
    },
    
    lSpace = WIDTH/data.length;

    xScale = d3.scale.linear()
                .range([MARGINS.left, WIDTH - MARGINS.right])
                .domain([d3.min(data, function(d) {return d.year;}), d3.max(data, function(d) {return d.year;})]),
                        
    yScale = d3.scale.linear()
                .range([HEIGHT - MARGINS.top, MARGINS.bottom])
                .domain([0, d3.max(data, function(d) {return d.sale;})]),
                //.domain([d3.min(data, function(d) {return d.sale;}), d3.max(data, function(d) {return d.sale;})]),

    xAxis = d3.svg.axis()
    .scale(xScale),

    yAxis = d3.svg.axis()
    .scale(yScale)
    .orient("left");

vis.append("svg:g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + (HEIGHT - MARGINS.bottom) + ")")
    .call(xAxis);

vis.append("svg:g")
    .attr("class", "y axis")
    .attr("transform", "translate(" + (MARGINS.left) + ",0)")
    .call(yAxis);

var lineGen = d3.svg.line()
    .x(function(d) {
        return xScale(d.year);
    })
    .y(function(d) {
        return yScale(d.sale);
    })
    //.interpolate("basis");

vis.append('svg:path')
    .attr('d', lineGen(data))
    .attr('stroke', 'green')
    .attr('stroke-width', 2)
    .attr('fill', 'none');

//vis.append('svg:path')
//    .attr('d', lineGen(data2))
//    .attr('stroke', 'blue')
//    .attr('stroke-width', 2)
//    .attr('fill', 'none');

vis.append("text")
    .attr("x", WIDTH/2)
    .attr("y", HEIGHT)
    .style("fill", "black")
    .attr("class","legend")
    .text("hours slept");

vis.append("text")
    .attr("x", 0)
    .attr("y", HEIGHT/2)
    .style("fill", "black")
    .attr("class","legend")
    .text("# days");