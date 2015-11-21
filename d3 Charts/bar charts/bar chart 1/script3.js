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
  
d3.select('#chart').append('svg')
    .attr('width', width)
    .attr('height', height)
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