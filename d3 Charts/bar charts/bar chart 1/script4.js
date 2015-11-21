// bar chart with ordinal scale
var bardata = [4, 5, 12, 9, 10, 8];

var height = 400,
    width = 600,
    barWidth = 50,
    barOffset = 7;

var colors = d3.scale.linear()
    .domain([0, d3.max(bardata)])
    .range(['#00FF00', '#005500'])

var yScale = d3.scale.linear()
        .domain([0, d3.max(bardata)])
        .range([0, height])
        
var xScale = d3.scale.ordinal()
        .domain(d3.range(0, bardata.length))
        .rangeBands([0, width])
  
var myChart = d3.select('#chart').append('svg')
    .attr('width', width)
    .attr('height', height)
    .append('g')
    .style('background', '#FFFFFF')
    .selectAll('rect').data(bardata)
    .enter().append('rect')
        .style('fill', colors)
        .attr('width', xScale.rangeBand() - barOffset)
        .attr('height', function(d) {
            return yScale(d);
        })
        .attr('x', function(d,i) {
            return xScale(i);
        })
        .attr('y', function(d) {
            return height - yScale(d);
        })
    .on('mouseover', function(d){
        d3.select(this)
            .transition().duration(75)
            .style('opacity', .5)
    })
    .on('mouseout', function(d){
        d3.select(this)
            .transition().duration(75)
            .style('opacity', 1)
    })

var vGuideScale = d3.scale.linear()
    .domain([0, d3.max(bardata)])
    .range([height, 0])
    
var vAxis = d3.svg.axis()
    .scale(vGuideScale)
    .orient('left')
    .ticks(10)
    
var vGuide = d3.select('svg').append('g')
    vAxis(vGuide)
    vGuide.attr('transform', 'translate(35, 10)')
    vGuide.selectAll('path')
        .style({ fill: 'none', stroke: "#000"})
    vGuide.selectAll('line')
        .style({stroke: "#000"})

var hAxis = d3.svg.axis()
    .scale(xScale)
    .orient('bottom')
    .tickValues(xScale.domain().filter(function(d, i) {
        return !(i % (bardata.length/5));
    }))
    
var hGuide = d3.select('svg').append('g')
    hAxis(hGuide)
    hGuide.attr('transform', 'translate(0, ' + (height - 30) + ')')
    hGuide.selectAll('path')
        .style({ fill: 'none', stroke: "#000"})
    hGuide.selectAll('line')
        .style({stroke: "#000"})


