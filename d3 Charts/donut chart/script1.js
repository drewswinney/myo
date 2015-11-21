var data = [
    { label: 'Sun', count: 8 }, 
    { label: 'Mon', count: 7 },
    { label: 'Tues', count: 6 },
    { label: 'Wed', count: 9 },
    { label: 'Thur', count: 8 },
    { label: 'Fri', count: 5 },
    { label: 'Sat', count: 11 }
];
var width = 360;
var height = 360;
var radius = Math.min(width, height) / 2;
var donutWidth = 75;
var legendRectSize = 18;                                  
var legendSpacing = 4;                                    
var color = d3.scale.category10();
var svg = d3.select('#chart')
    .append('svg')
    .attr('width', width)
    .attr('height', height)
    .append('g')
    .attr('transform', 'translate(' + (width / 2) + 
        ',' + (height / 2) + ')');
var arc = d3.svg.arc()
    .innerRadius(radius - donutWidth)
    .outerRadius(radius);
var pie = d3.layout.pie()
    .value(function(d) { return d.count; })
    .sort(null);
var path = svg.selectAll('path')
    .data(pie(data))
    .enter()
    .append('path')
    .attr('d', arc)
    .attr('fill', function(d, i) { 
        return color(d.data.label);
    });
var legend = svg.selectAll('.legend')                     
    .data(color.domain())                                   
    .enter()                                                
    .append('g')                                            
    .attr('class', 'legend')
    .attr('transform', function(d, i) {                     
        var height = legendRectSize + legendSpacing;          
        var offset =  height * color.domain().length / 2;     
        var horz = -2.5 * legendRectSize;                       
        var vert = i * height - offset;                       
        return 'translate(' + horz + ',' + vert + ')';        
    });                                                     
legend.append('rect')                                     
    .attr('width', legendRectSize)                          
    .attr('height', legendRectSize)                         
    .style('fill', color)                                   
    .style('stroke', color);                                
  
legend.append('text')                                     
    .attr('x', legendRectSize + legendSpacing)              
    .attr('y', legendRectSize - legendSpacing)              
    .text(function(d, i) {return data[i].label + " - " + data[i].count + "hrs";});

