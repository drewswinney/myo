var data = [
    {"date":"2012-03-20","total":6},
    {"date":"2012-03-21","total":8},
    {"date":"2012-03-22","total":9},
    {"date":"2012-03-23","total":7},
    {"date":"2012-03-24","total":8},
    {"date":"2012-03-25","total":6},
    {"date":"2012-03-26","total":5},
    {"date":"2012-03-27","total":4},
    {"date":"2012-03-28","total":12}
    ];

var margin = {top: 40, right: 40, bottom: 70, left:130},
    width = 1000,
    height = 500;

var x = d3.time.scale()
    .domain([new Date(data[0].date), d3.time.day.offset(new Date(data[data.length - 1].date), 1)])
    .rangeRound([0, width - margin.left - margin.right]);

var y = d3.scale.linear()
    .domain([0, d3.max(data, function(d) { return d.total; })])
    .range([height - margin.top - margin.bottom, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient('bottom')
    .ticks(d3.time.days, 1)
    .tickFormat(d3.time.format('%m/%d'))
    .tickSize(0)
    .tickPadding(15);

var yAxis = d3.svg.axis()
    .scale(y)
    .orient('left')
    .tickSize(15)
    .tickPadding(15);

var svg = d3.select('#chart').append('svg')
    .attr('class', 'chart')
    .attr('width', width)
    .attr('height', height)
    .append('g')
    .attr('transform', 'translate(' + margin.left + ', ' + margin.top + ')');

svg.selectAll('.chart')
    .data(data)
    .enter().append('rect')
    .attr('class', 'bar')
    .attr('x', function(d) { return x(new Date(d.date)); })
    .attr('y', function(d) { return height - margin.top - margin.bottom - (height - margin.top - margin.bottom - y(d.total)) })
    .attr('width', 50)
    .attr('height', function(d) { return height - margin.top - margin.bottom - y(d.total) })
svg.append('g')
    .attr('class', 'x axis')
    .attr('transform', 'translate(0, ' + (height - margin.top - margin.bottom) + ')')
    .call(xAxis);

svg.append('g')
  .attr('class', 'y axis')
  .call(yAxis);
  
svg.append("text")
    .attr('transform', 'translate(' + ((width - margin.right - margin.left - 20) / 2) + ', ' + (height - margin.top - margin.bottom + 60) + ')')
    .attr("text-anchor", "middle")
    .text("Day");

svg.append("text")
    .attr('transform', 'translate(-120, ' + (height - margin.top - margin.bottom) / 2 + ')')
    .text("Hours");

svg.append("text")
    .attr('transform', 'translate(' + ((width - margin.right - margin.left - 20) / 2 - 37)  + ',-20)')
    .style("text-decoration", "underline")
    .text("Sleep Data");

