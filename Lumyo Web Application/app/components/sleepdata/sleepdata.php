<?php 
 header("Access-Control-Allow-Origin: myo.local");
?>

<link rel="stylesheet" type="text/css" href="../../../assets/css/views/sleepdata.css">

<div class="modal fade" id="session-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 id="modalTitle" class="modal-title"></h4>
      </div>
      <div id="modalBody" class="modal-body">
        <ul class="nav nav-tabs" id="sessionTabs">
        </ul>
      	<svg id="visualization"></svg>
      </div>
      <div class="modal-footer">
      	<div class="row">
      		<div class="col-md-3">
      			<ul class="list-inline pull-left">
      				<li><h5 style="font-weight: bold;" id="startTimeLabel">Start Time: </h5></li>
      				<li><h5 id="startTime"></h5></li>
      			</ul>
      		</div>
      		<div class="col-md-3">
      			<ul class="list-inline">
      				<li class="pull-left"><h5 style="font-weight: bold;" id="endTimeLabel">End Time: </h5></li>
      				<li class="pull-left"><h5 id="endTime"></h5></li>
      			</ul>
      		</div>
            <div class="col-md-3">
      			<ul class="list-inline">
	      			<li class="pull-left"><h5 style="font-weight: bold;" id="qualityLabel">Quality: </h5></li>
	      			<li class="pull-left"><h5 id="quality"></h5></li>
                    <li class="pull-right"><h5 id="type"></h5></li>
                    <li class="pull-right"><h5 style="font-weight: bold;" id="typeLabel">Type: </h5></li>
	      		</ul>
	      	</div>
            <div class="col-md-3">
      			<ul class="list-inline">
                    <li class="pull-right"><div class="btn btn-primary" id="rotationBtn" data-toggle="tooltip" data-placement="top" title="Rotation">R</div></li>
                    <li class="pull-right"><div class="btn btn-warning" id="orientationBtn" data-toggle="tooltip" data-placement="top" title="Orienatation">O</div></li>
                    <li class="pull-right"><div class="btn btn-danger" id="accelerationBtn" data-toggle="tooltip" data-placement="top" title="Acceleration">A</div></li>
                    <li class="pull-right"><div class="btn btn-success active" id="emgBtn" data-toggle="tooltip" data-placement="top" title="EMG">E</div></li>
	      		</ul>
	      	</div>
      	</div>
      </div>
    </div>
  </div>
</div>

<div class="page-header" style="margin-top:10px;">
	<div class="row">
		<h2 class="col-md-3 title pull-left" style="color:#f9f9f9">Sessions</h2>
        <div class="col-md-6">
            <div class="row">
                <span class="col-md-4 glyphicon glyphicon-chevron-left text-center" onclick="moveBackMonth()" style="color:#f9f9f9; margin-top: 35px;"></span>
                <h2 id="currentMonthLabel" class="col-md-3 text-center" style="color:#f9f9f9"></h2>
                <h4 id="currentYearLabel" class="col-md-1" style="color:#f9f9f9"></h4>
                <span class="col-md-4 glyphicon glyphicon-chevron-right text-center" onclick="moveForwardMonth()" style="color:#f9f9f9; margin-top:35px;"></span>
            </div>
        </div>
		<div class="col-md-3 pull-right">
			<ul class="list-inline pull-right" style="margin-top:20px; margin-right:20px;">
				<li style="height=40px;"> 
					<div class="btn-group" role="group" aria-label="...">
					  <button id="calendarViewBtn" type="button" class="btn btn-default"><span class="glyphicon glyphicon-th"></span></button>
					  <button id="listViewBtn" type="button" class="btn btn-default"><span class="glyphicon glyphicon-th-list"></span></button>
					</div>
				</li>
			</div>
		</div>
	</div>
</div>
<div id="calendarView">
	<ul class="calendarHeaderRow">
		<li><div class="calendarDayHeader"><h5>Sunday</h5></div></li>
		<li><div class="calendarDayHeader"><h5>Monday</h5></div></li>
		<li><div class="calendarDayHeader"><h5>Tuesday</h5></div></li>
		<li><div class="calendarDayHeader"><h5>Wednesday</h5></div></li>
		<li><div class="calendarDayHeader"><h5>Thursday</h5></div></li>
		<li><div class="calendarDayHeader"><h5>Friday</h5></div></li>
		<li><div class="calendarDayHeader"><h5>Saturday</h5></div></li>
	</ul>
	<ul id="week1" class="calendarRow">
	</ul>
	<ul id="week2" class="calendarRow">
	</ul>
	<ul id="week3" class="calendarRow">
	</ul>
	<ul id="week4" class="calendarRow">
	</ul>
	<ul id="week5" class="calendarRow">
	</ul>
</div>
<div class="hidden" id="listView">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<ul class="list-group list" id="sessionList">
			</ul>
		</div>
	</div>
</div>

<script src="../../../assets/js/views/sleepdata.js"></script>