var sessions = new Array();
var sessionTypes = new Array();
var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
var colors = ['1B452A', '225635', '286740', '2F784A', '368A55', '3C9B5F', '43AC6A', '56B479', '69BD88', '7BC597'];
var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

var date = new Date();
var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 1);

var currentGraphType = 'E';
var currentSessionID = 0;
var currentday = 0;

$('#accelerationBtn').click(function()
{
    $(this).addClass('active');
    $('#emgBtn').removeClass('active');
    $('#rotationBtn').removeClass('active');
    $('#orientationBtn').removeClass('active');
    currentGraphType = 'A';
    updateGraph(currentday);
});

$('#orientationBtn').click(function()
{
    $(this).addClass('active');
    $('#accelerationBtn').removeClass('active');
    $('#rotationBtn').removeClass('active');
    $('#emgBtn').removeClass('active');
    currentGraphType = 'O';
    updateGraph(currentday);
});

$('#rotationBtn').click(function()
{
    $('#accelerationBtn').removeClass('active');
    $('#emgBtn').removeClass('active');
    $('#orientationBtn').removeClass('active');
    $(this).addClass('active');
    currentGraphType = 'R';
    updateGraph(currentday);
});

$('#emgBtn').click(function()
{
    $(this).addClass('active');
    $('#accelerationBtn').removeClass('active');
    $('#rotationBtn').removeClass('active');
    $('#orientationBtn').removeClass('active');
    currentGraphType = 'E';
    updateGraph(currentday);
});

$('#listViewBtn').click(function()
{
	if($('#listView').hasClass('hidden'))
    $('#listView').removeClass('hidden');
	$('#singleView').addClass('hidden');
	$('#calendarView').addClass('hidden');
	$('#sortButtons').removeClass('hidden');
	$('#sortLabel').removeClass('hidden');
});

$('#calendarViewBtn').click(function()
{
	$('#listView').addClass('hidden');
	$('#singleView').addClass('hidden');
	if($('#calendarView').hasClass('hidden'))
    $('#calendarView').removeClass('hidden');
	$('#sortButtons').addClass('hidden');
	$('#sortLabel').addClass('hidden');
});

var sessionTypesUrl = "http://drewswinney.com:8080/api/sessiontype";
$.get(sessionTypesUrl, function(data, status)
{
    //Populate the sessionTypes
    $.each(data, function(i, item) 
	{
        sessionTypes[item.id] = item.stName;
    });
});

var url = "http://drewswinney.com:8080/api/sessionsbyloginid/"+ getCookie('loginId') + "?sessionStartTime=" + firstDay.toISOString().slice(0, 19).replace('T', ' ') + "&sessionEndTime=" + lastDay.toISOString().slice(0, 19).replace('T', ' ');

$.get(url, function(data, status)
{
    sessions = new Array();
    $('#currentMonthLabel').empty();
    $('#currentMonthLabel').append(months[firstDay.getMonth()]);
    $('#currentYearLabel').empty();
    $('#currentYearLabel').append(date.getFullYear());
    
    $('#sessionList').empty();
        
    if(data == '')
        $('#sessionList').append('<li style="color:#ffffff; list-style-type: none;"><h4>There are no sessions this month</h4></li>');

	//Populate the sessions into the sessions array
	$.each(data, function(i, item) 
	{
		var t = item.sessionStartTime.split(/[- :]/);
		var startDateTime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

	    t = item.sessionEndTime.split(/[- :]/);
		var endDateTime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

		var sessionDuration = Math.round((endDateTime - startDateTime)/1000);

		var sessionData = {sessionID: item.id, sessionStartTime: item.sessionStartTime, sessionEndTime: item.sessionEndTime, sessionQuality: item.sessionQuality, sessionDuration: sessionDuration, sessionType: item.sessionTypeID};
        
        var t = sessionData.sessionStartTime.split(/[- :]/);
        var sessionStartTime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
        
        if (sessions[sessionStartTime.getDate()] != undefined)    
        {
            sessions[sessionStartTime.getDate()].push(sessionData);
        }
        else
        {
            sessions[sessionStartTime.getDate()] = new Array(sessionData);
        }
	});

	var currentDate = 1;
	var week = 1;
	var lastDayOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0);
	for(i = 1; i < 36; i++)
	{

		if(i > firstDay.getDay() && currentDate <= lastDayOfMonth.getDate())
		{
			if(sessions[currentDate] != undefined)
			{
                //Compute overall session quality from multiple sessions
                var totalSessionQuality = 0;
                for(j = 0; j < sessions[currentDate].length; j++)
                {
                    totalSessionQuality += parseInt(sessions[currentDate][j].sessionQuality);
                }
                
                //Computer average session quality from the total
                var averageSessionQuality = totalSessionQuality / sessions[currentDate].length;
                console.log(averageSessionQuality);
                
                //Add the square to the calendar view and a list item to the list view
                $('#week' + week).append('<li onclick="openSession(' + currentDate + ')"><div class="calendarDay" style="background:#' +      colors[Math.floor(averageSessionQuality / 10) - 1] + ';"><p>' + currentDate + '</p></div></li>');
				$('#sessionList').append('<li class="listitem list-group-item" onclick="openSession(' + currentDate + ')"><div class="pull-right" style="width:40px; height:40px; background:#' + colors[Math.floor(averageSessionQuality / 10) - 1] + '"></div><h4>' + months[firstDay.getMonth()] + ' ' + currentDate + '</h4></li>');
			}
			else
			{
				$('#week' + week).append('<li><div class="emptyDay"><h4 style="position:relative; top:20px; color:#ffffff;">' + currentDate + '</h4></div></li>');
			}
			currentDate++;
		}
		else
		{
			$('#week' + week).append('<li><div class="emptyDay"></div></li>');
		}

		if(i % 7 == 0 && i > 0)
			week++;
	}
});

function moveBackMonth()
{
    moveMonth(-1);
}

function moveForwardMonth()
{
    moveMonth(1);
}

function moveMonth(move)
{
    sessions = new Array();
    date.setMonth(date.getMonth() + move);
    firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 1);
    
    var url = "http://drewswinney.com:8080/api/sessionsbyloginid/"+ getCookie('loginId') + "?sessionStartTime=" + firstDay.toISOString().slice(0, 19).replace('T', ' ') + "&sessionEndTime=" + lastDay.toISOString().slice(0, 19).replace('T', ' ');

    $.get(url, function(data, status)
    {
        for(i = 0; i < 6; i++)
        {
            $('#week' + i).empty();
        }
  
        $('#sessionList').empty();
        
        if(data == '')
            $('#sessionList').append('<li style="color:#ffffff; list-style-type: none;"><h4>There are no sessions this month</h4></li>');
        
        $('#currentMonthLabel').empty();
        $('#currentMonthLabel').append(months[firstDay.getMonth()]);
        $('#currentYearLabel').empty();
        $('#currentYearLabel').append(date.getFullYear());
        
        //Populate the sessions into the sessions array
        $.each(data, function(i, item) 
        {       
            var t = item.sessionStartTime.split(/[- :]/);
            var startDateTime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

            t = item.sessionEndTime.split(/[- :]/);
            var endDateTime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

            var sessionDuration = Math.round((endDateTime - startDateTime)/1000);

            var sessionData = {sessionID: item.id, sessionStartTime: item.sessionStartTime, sessionEndTime: item.sessionEndTime, sessionQuality: item.sessionQuality, sessionDuration: sessionDuration, sessionType: item.sessionTypeID};

            var t = sessionData.sessionStartTime.split(/[- :]/);
            var sessionStartTime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

            if (sessions[sessionStartTime.getDate()] != undefined)    
            {
                sessions[sessionStartTime.getDate()].push(sessionData);
            }
            else
            {
                sessions[sessionStartTime.getDate()] = new Array(sessionData);
            }
        });

        var currentDate = 1;
        var week = 1;
        var lastDayOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        for(i = 1; i < 36; i++)
        {

            if(i > firstDay.getDay() && currentDate <= lastDayOfMonth.getDate())
            {
                if(sessions[currentDate] != undefined)
                {
                    //Compute overall session quality from multiple sessions
                    var totalSessionQuality = 0;
                    for(j = 0; j < sessions[currentDate].length; j++)
                    {
                        totalSessionQuality += parseInt(sessions[currentDate][j].sessionQuality);
                    }

                    //Computer average session quality from the total
                    var averageSessionQuality = totalSessionQuality / sessions[currentDate].length;

                    //Add the square to the calendar view and a list item to the list view
                    $('#week' + week).append('<li onclick="openSession(' + currentDate + ')"><div class="calendarDay" style="background:#' +      colors[(Math.floor(averageSessionQuality) / 10) - 1] + ';"><p>' + currentDate + '</p></div></li>');
                    $('#sessionList').append('<li class="listitem list-group-item" onclick="openSession(' + currentDate + ')"><div class="pull-right" style="width:40px; height:40px; background:#' + colors[Math.floor(averageSessionQuality / 10) - 1] + '"></div><h4>' + months[firstDay.getMonth()] + ' ' + currentDate + '</h4></li>');
                }
                else
                {
                    $('#week' + week).append('<li><div class="emptyDay"><h4 style="position:relative; top:20px; color:#ffffff;">' + currentDate + '</h4></div></li>');
                }
                currentDate++;
            }
            else
            {
                $('#week' + week).append('<li><div class="emptyDay"></div></li>');
            }

            if(i % 7 == 0 && i > 0)
                week++;
        }
    });
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

function openSession(id)
{
	$('#modalTitle').replaceWith('<h3 id="modalTitle" class="modal-title">' + months[firstDay.getMonth()] + ' ' + id + '</h3>');
	$('#startTime').replaceWith('<h5 id="startTime">' + sessions[id][0].sessionStartTime + '</h5>');
	$('#endTime').replaceWith('<h5 id="endTime">' + sessions[id][0].sessionEndTime + '</h5>');
	$('#quality').replaceWith('<h5 id="quality">' + sessions[id][0].sessionQuality + '</h5>');
    $('#type').replaceWith('<h5 id="type">' + sessionTypes[sessions[id][0].sessionType] + '</h5>');
    
    currentSessionID = 0;
    currentday = id;
    
    updateTabs(id);
	updateGraph(id);

	var options = { show: true };

  	$('#session-modal').modal(options);
}

function updateTabs(id)
{
    $('#sessionTabs').empty();
    for(i = 0; i < sessions[id].length; i++)
    {
        if(i == 0)
        {
             $('#sessionTabs').append('<li role="presentation" class="active" id="sessionTab_' + i + '" onclick="updateFooter(' + id + ', ' + i + ')"><a>Session ' + (i + 1)+ '</a></li>');
        }
        else
        {
             $('#sessionTabs').append('<li role="presentation" id="sessionTab_' + i + '" onclick="updateFooter(' + id + ', ' + i + ')"><a>Session ' + (i + 1) + '</a></li>');
        }
    }
}

function updateFooter(id, session)
{
    for(i = 0; i < sessions[id].length; i++)
    {
        $('#sessionTab_' + i).removeClass('active');
    }
    
    currentday = id;
    currentSessionID = session;
    updateGraph(id);

    $('#sessionTab_' + session).addClass('active');
	$('#startTime').replaceWith('<h5 id="startTime">' + sessions[id][session].sessionStartTime + '</h5>');
	$('#endTime').replaceWith('<h5 id="endTime">' + sessions[id][session].sessionEndTime + '</h5>');
	$('#quality').replaceWith('<h5 id="quality">' + sessions[id][session].sessionQuality + '</h5>');
    $('#type').replaceWith('<h5 id="type">' + sessionTypes[sessions[id][session].sessionType] + '</h5>');
}

function updateGraph(id)
{
    d3.select("svg").selectAll('*').remove();
    
    //Grab data using AJAX
    var graphdata = new Array();
    switch(currentGraphType)
    {
        case 'E':
            $.get('http://drewswinney.com:8080/api/emgdatapointsbysessionid/' + sessions[id][currentSessionID].sessionID, function(data, status)
            {
                graphdata.push(new Array());
                graphdata.push(new Array());
                graphdata.push(new Array());
                graphdata.push(new Array());
                graphdata.push(new Array());
                graphdata.push(new Array());
                graphdata.push(new Array());
                graphdata.push(new Array());
                //Populate the emgdatapoints into the graph
                $.each(data, function(i, item)
                {
                    var t = item.emgpDateTime.split(/[- :]/);
                    var dateTime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                    graphdata[0].push({x: dateTime, y: item.emgpPod1});
                    graphdata[1].push({x: dateTime, y: item.emgpPod2});
                    graphdata[2].push({x: dateTime, y: item.emgpPod3});
                    graphdata[3].push({x: dateTime, y: item.emgpPod4});
                    graphdata[4].push({x: dateTime, y: item.emgpPod5});
                    graphdata[5].push({x: dateTime, y: item.emgpPod6});
                    graphdata[6].push({x: dateTime, y: item.emgpPod7});
                    graphdata[7].push({x: dateTime, y: item.emgpPod8});
                });
                showGraph(graphdata);
            });
            break;
        case 'A':
            $.get('http://drewswinney.com:8080/api/accelerationdatapointsbysessionid/' + sessions[id][currentSessionID].sessionID, function(data, status)
            {
                graphdata.push(new Array());
                graphdata.push(new Array());
                graphdata.push(new Array());
                //Populate the emgdatapoints into the graph
                $.each(data, function(i, item) 
                {
                    var t = item.adpDateTime.split(/[- :]/);
                    var dateTime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                    graphdata[0].push({x: dateTime, y: item.adpXAcceleration});
                    graphdata[1].push({x: dateTime, y: item.adpYAcceleration});
                    graphdata[2].push({x: dateTime, y: item.adpZAcceleration});
                });
                showGraph(graphdata);
            });
            break;
        case 'O':
            $.get('http://drewswinney.com:8080/api/orientationdatapointsbysessionid/' + sessions[id][currentSessionID].sessionID, function(data, status)
            {
                graphdata.push(new Array());
                graphdata.push(new Array());
                graphdata.push(new Array());
                //Populate the emgdatapoints into the graph
                $.each(data, function(i, item) 
                {
                    var t = item.odpDateTime.split(/[- :]/);
                    var dateTime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                    graphdata[0].push({x: dateTime, y: item.odpXRotation});
                    graphdata[1].push({x: dateTime, y: item.odpYRotation});
                    graphdata[2].push({x: dateTime, y: item.odpZRotation});
                });
                showGraph(graphdata);
            });
            break;
        case 'R':
            $.get('http://drewswinney.com:8080/api/rotationdatapointsbysessionid/' + sessions[id][currentSessionID].sessionID, function(data, status)
            {
                graphdata.push(new Array());
                graphdata.push(new Array());
                graphdata.push(new Array());
                //Populate the emgdatapoints into the graph
                $.each(data, function(i, item) 
                {
                    var t = item.rdpDateTime.split(/[- :]/);
                    var dateTime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                    graphdata[0].push({x: dateTime, y: item.rdpXRotation});
                    graphdata[1].push({x: dateTime, y: item.rdpYRotation});
                    graphdata[2].push({x: dateTime, y: item.rdpZRotation});
                });
                showGraph(graphdata);
            });
            break;
    }
}
    
    
function showGraph(graphdata)
{
	var vis = d3.select("#visualization"),
    WIDTH = 1000,
    HEIGHT = 500,
    MARGINS = {
        top: 50,
        right: 20,
        bottom: 50,
        left: 100
    };
    
    var max, min;
    if(currentGraphType == 'A' || currentGraphType == 'O')
    {
        max = 1;
        min = -1;
    }
    else if (currentGraphType == 'E')
    {
        max = 120;
        min = -120;
    }
    else
    {
        min = -180;
        max = 180;
    }
    
    console.log('Min: ' + min);
    console.log('Max: ' + max)
    
    var xScale = d3.time.scale()
                .range([MARGINS.left, WIDTH - MARGINS.right])
                .domain([d3.min(graphdata[0], function(d) {return d.x;}), d3.max(graphdata[0], function(d) {return d.x;})]),
                        
    yScale = d3.scale.linear()
                .range([HEIGHT - MARGINS.top, MARGINS.bottom])
                .domain([min, max]),
        
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
	        return xScale(d.x);
	    })
	    .y(function(d) {
	        return yScale(d.y);
	    })
        .interpolate("basis");
    
    var colors =  ['red', 'green', 'orange', 'blue', 'yellow', 'purple', 'black', 'pink'];
    
    //Draw the lines for the graph
    for (i = 0; i < graphdata.length; i++)
    {
        vis.append('svg:path')
	    .attr('d', lineGen(graphdata[i]))
	    .attr('stroke', colors[i])
	    .attr('stroke-width', 2)
	    .attr('fill', 'none');
    }

    //Draw legend text
	vis.append("text")
	    .attr("x", WIDTH/2)
	    .attr("y", HEIGHT)
	    .style("fill", "black")
	    .attr("class","legend")
	    .text('Time');

    switch(currentGraphType)
    {
        case 'A': 
            vis.append("text")
	    .attr("x", 0)
            .attr("y", HEIGHT/2)
            .style("fill", "black")
            .attr("class","legend")
            .text('Accel.');
            break;
        case 'E': 
            vis.append("text")
            .attr("x", 0)
            .attr("y", HEIGHT/2)
            .style("fill", "black")
            .attr("class","legend")
            .text('EMG');
            break;
        case 'R':
            vis.append("text")
            .attr("x", 0)
            .attr("y", HEIGHT/2)
            .style("fill", "black")
            .attr("class","legend")
            .text('Rotation');
            break;
        case 'O': 
            vis.append("text")
            .attr("x", 0)
            .attr("y", HEIGHT/2)
            .style("fill", "black")
            .attr("class","legend")
            .text('Orien.');
            break;
    }
}