var url = "http://drewswinney.com:8080/api/dashboardgraphbyloginid/" + getCookie('loginId');
var graphs = new Array();
var graphTypes = new Array();

function togglePanel(panelName)
{
    var $this = $('#' + panelName);
	if(!$this.hasClass('panel-collapsed')) 
    {
        console.log($this.parents('.panel').find('.panel-body'));
		$this.find('.panel-body').slideUp();
		$this.addClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
	} 
    else 
    {
		$this.find('.panel-body').slideDown();
		$this.removeClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
	}
}

$.get('http://drewswinney.com:8080/api/dashboardgraphtype', function(data, status)
{
    if (data != '')
    {
        $.each(data, function(i, item)
        {
            graphTypes[item.id] = { id: item.id, name: item.dgtName, subtype: item.dgtSubType, deleted: item.dgtDeleted };
            console.log('GraphType: ' + item.id + ', i: ' + i);
        });
    }
}).done(function()
{
   $.get(url, function(data, status)
    {   
        if (data != "")
        {
            var maxRowCount = 0;
            $.each(data, function(i, item)
            {
                if (maxRowCount < item.dgRow)
                    maxRowCount = item.dgRow;
                //load the information into the view using jquery
                graphs[item.id] = {id: item.id, size: item.dgSize, row: item.dgRow, typeID: item.dgtID};
            });

            for(i = 1; i <= maxRowCount; i++)
            {
                $('#dashboard').append('<div class="row" id="row' + i + '">');
            }

            graphs.forEach(function (graph)
            {
                createWidget(graph);
            });
        }
    }); 
});

function createWidget(graph)
{
    var size = 4;
    switch(graph.size)
    {
        case 'S': size = 4; break;
        case 'M': size = 6; break;
        case 'L': size = 12; break;
        default: break;
    }

    var colors = ['danger', 'success', 'info', 'primary', 'default', 'warning'];

    var colorChoice = Math.floor((Math.random() * 5) + 1);

    $('#row' + graph.row).append('<div class="col-md-' + size + '"><div class="panel panel-' + colors[colorChoice] + '" id="panel-' + graph.id + '"><div class="panel-heading"><h3 class="panel-title">' + graphTypes[graph.typeID].name + '</h3><span class="pull-right clickable" onclick="togglePanel(\'panel-' + graph.id + '\')"><i class="glyphicon glyphicon-chevron-up"></i></span></div><div id="panel-body-' + graph.id + '" class="panel-body"></div></div>');

    switch(graphTypes[graph.typeID].subtype)
    {
        case 'Bar':
            if(graph.typeID == 1)
            {
                    //Get the data for the bar chart and then load it into the panel
                    bardata = [['Sun', 'Mon', 'Tues', 'Wed', 'Thur', 'Fri', 'Sat'], [0, 0, 0, 0, 0, 0, 0]];
                    $.get('http://drewswinney.com:8080/api/dashboardhelper/hourssleptperday/' + getCookie('loginId'), function(data, status)
                    {
                        if (data != '')
                        {
                            bardata = data;
                        }
                    }).done(function()
                    {
                       loadBarChart(bardata, graph);  
                    });
            }
            else
            {
                 //Get the data for the bar chart and then load it into the panel
                    bardata = [['Sun', 'Mon', 'Tues', 'Wed', 'Thur', 'Fri', 'Sat'], [0, 0, 0, 0, 0, 0, 0]];
                    $.get('http://drewswinney.com:8080/api/dashboardhelper/hoursfitnessperday/' + getCookie('loginId'), function(data, status)
                    {
                        if (data != '')
                        {
                            bardata = data;
                        }
                    }).done(function()
                    {
                       loadBarChart(bardata, graph);  
                    });
            }
            break;
         case 'Pie':
            if(graph.typeID == 3)
            {
                //Get the data for the pie chart and then load it into the panel
                var piedata = [{"label":"Sleep", "value":60}, {"label":"Fitness", "value":40}];
                $.get('http://drewswinney.com:8080/api/dashboardhelper/fitnessvssleep/' + getCookie('loginId'), function(data, status)
                {
                    if (data != '')
                    {
                        piedata = data;
                    }
                }).done(function()
                {
                    loadPieChart(piedata, graph);
                });
            }
            else
            {
                //Get the data for the pie chart and then load it into the panel
                var piedata = [{"label":"Sleep", "value":60}, {"label":"Fitness", "value":40}];
                $.get('http://drewswinney.com:8080/api/dashboardhelper/fitnessvsgoal/' + getCookie('loginId'), function(data, status)
                {
                    if (data != '')
                    {
                        piedata = data;
                    }
                }).done(function()
                {
                    loadPieChart(piedata, graph);
                });
            }
            break;
         case 'Number':
            if (graph.typeID == 5)
            {
                //Get the data for the number chart and then load it into the panel
                var number = 0;
                $.get('http://drewswinney.com:8080/api/dashboardhelper/averagehourssleep/' + getCookie('loginId'), function(data, status)
                {
                    if (data != '')
                    {
                        number = data;
                    }
                }).done(function()
                {
                    loadNumberChart(number, graph);
                });
            }
            else
            {
                //Get the data for the number chart and then load it into the panel
                var number = 0;
                $.get('http://drewswinney.com:8080/api/dashboardhelper/averagehoursfitness/' + getCookie('loginId'), function(data, status)
                {
                    if (data != '')
                    {
                        number = data;
                    }
                }).done(function()
                {
                    loadNumberChart(number, graph);
                });
            }
            break;
    }
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function loadBarChart(bardata, graph)
{
    var barOffset = 4;
    var height, width, barWidth;
    
    switch(graph.size)
    {
        case 'S': 
            height = $('#panel-body-' + graph.id).width() * .666;
            width = $('#panel-body-' + graph.id).width();
            barWidth = ($('#panel-body-' + graph.id).width() - (barOffset * bardata[1].length) - 40) / (bardata[1].length);
            break;
        case 'M':
            height = $('#panel-body-' + graph.id).width() * .666;
            width = $('#panel-body-' + graph.id).width();
            barWidth = ($('#panel-body-' + graph.id).width() - (barOffset * bardata[1].length) - 40) / (bardata[1].length);
            break;
        case 'L':
            height = $('#panel-body-' + graph.id).width() * .333;
            width = $('#panel-body-' + graph.id).width();
            barWidth = ($('#panel-body-' + graph.id).width() - (barOffset * bardata[1].length) - 40) / (bardata[1].length);
            break;
    }
        
    var colors = d3.scale.linear()
        .domain([0, d3.max(bardata[1])])
        .range(['#00FF00', '#005500'])

    var yScale = d3.scale.linear()
            .domain([0, d3.max(bardata[1])])
            .range([0, height])

    var xScale = d3.scale.ordinal()
            .domain(bardata[0])
            .rangeBands([0, width - 45])
    
    var myChart = d3.select('#panel-body-' + graph.id).append('svg')
        .attr('width', width)
        .attr('height', height + 30)
        .append('g')
        .style('background', '#FFFFFF')
        .selectAll('rect').data(bardata[1])
        .enter().append('rect')
            .style('fill', colors)
            .attr('width', barWidth)
            .attr('height', function(d) {
                return yScale(d);
            })
            .attr('x', function(d,i) {
                return 40 + (i * (barWidth + barOffset));
            })
            .attr('y', function(d) {
                return height - yScale(d) + 10;
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
        .domain([0, d3.max(bardata[1])])
        .range([height, 0])

    var vAxis = d3.svg.axis()
        .scale(vGuideScale)
        .orient('left')
        .ticks(10)

    var vGuide = d3.select('#panel-body-' + graph.id).select('svg').append('g')
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
            return bardata[0][i];
        }))

    var hGuide = d3.select('#panel-body-' + graph.id).select('svg').append('g')
        hAxis(hGuide)
        hGuide.attr('transform', 'translate(40, ' + (height + 10) + ')')
        hGuide.selectAll('path')
            .style({ fill: 'none', stroke: "#000"})
        hGuide.selectAll('line')
            .style({stroke: "#000"})
}

function loadPieChart(piedata, graph)
{
    var width, height;
    switch(graph.size)
    {
        case 'S': 
            height = $('#panel-body-' + graph.id).width() * .8;
            width = $('#panel-body-' + graph.id).width();
            break;
        case 'M':
            height = $('#panel-body-' + graph.id).width() * .8;
            width = $('#panel-body-' + graph.id).width();
            break;
        case 'L':
            height = $('#panel-body-' + graph.id).width() * .4;
            width = $('#panel-body-' + graph.id).width();
            break;
    }
    
    var r = height / 2;
    var color = d3.scale.category10();

    var vis = d3.select('#panel-body-' + graph.id).append("svg:svg").data([piedata]).attr("width", width).attr("height", height).append("svg:g").attr("transform", "translate(" + width / 2 + "," + (height - r)  + ")");
    var pie = d3.layout.pie().value(function(d){return d.value;});

    // declare an arc generator function
    var arc = d3.svg.arc().outerRadius(r);

    // select paths, use arc generator to draw
    var arcs = vis.selectAll("g.slice").data(pie).enter().append("svg:g").attr("class", "slice");
    arcs.append("svg:path")
        .attr("fill", function(d, i){
            return color(i);
        })
        .attr("d", function (d) {
            return arc(d);
        });

    arcs.append("svg:text").attr("transform", function(d){
                d.innerRadius = 0;
                d.outerRadius = r;
        return "translate(" + arc.centroid(d) + ")";}).attr("text-anchor", "middle").attr("fill", function(d, i) { return '#ffffff'; }).text( function(d, i) {
        return piedata[i].label + ": " + piedata[i].value + "%";});
}

function loadNumberChart(value, graph)
{
    $('#panel-body-' + graph.id).append('<div class="numberchart text-center">' + value + '</div>');
}

function editDashboard()
{
    $('#lockIcon').replaceWith('<span id="okIcon" class="glyphicon glyphicon-ok" onclick="endEditDashboard()"></span>');
    $('#lockIconLabel').hide();
    $('.dashboardToolbar').show();
}

function endEditDashboard()
{
    $('#okIcon').replaceWith('<span id="lockIcon" class="glyphicon glyphicon-lock" onclick="editDashboard()"></span>');
    $('#lockIconLabel').show();
    $('.dashboardToolbar').hide();
}
                             
                             
                             