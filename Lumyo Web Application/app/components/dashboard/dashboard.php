<?php 
 header("Access-Control-Allow-Origin: myo.local");
?>

<link href="../../assets/css/views/dashboard.css" rel="stylesheet">
<div id="dashboard">
</div>
<div class="row hidden">
    <div class="col-md-12">
        <hr style="color:#d3d3d3">
        <div class="row" style="margin: 0px;">
            <div class="col-md-4">
                <ul class="dashboardToolbarHeader">
                    <li class="dashboardToolbarItem "><span id="lockIcon" class="glyphicon glyphicon-lock" onclick="editDashboard()"></span></li>
                    <li class="dashboardToolbarItem" id="lockIconLabel">Click here to edit the Dashboard</li>
                </ul>
            </div>
            <div class="col-md-6">
                 <ul class="dashboardToolbar">
                    <li class="dashboardToolbarItem"><span id="addBtn" class="glyphicon glyphicon-plus"></span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="../../../assets/js/views/dashboard.js"></script>