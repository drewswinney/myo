<?php 
 header("Access-Control-Allow-Origin: myo.local");
?>

<div class="panel panel-success" style="margin-top:20px">
    <div class="panel-heading">
        <h4>Account Information</h4>
    </div>
    <div class="panel-body">
        <div class="col-md-6">
        	<div class="row" style="margin: 10px">
                <div class="col-md-6"><h4>First Name:</h4></div>
                <div class="col-md-6"><h5 id="firstname"></h5></div>
            </div>
            <div class="row" style="margin: 10px">
                <div class="col-md-6"><h4>Last Name:</h4></div>
                <div class="col-md-6"><h5 id="lastname"></h5></div>
             </div>
            <div class="row" style="margin: 10px">
                <div class="col-md-6"><h4>Username: </h4></div>
                <div class="col-md-6"><h5 id="username"></h5></div>
            </div>
            <div class="row" style="margin: 10px">
                <div class="col-md-6"><h4>Password: </h4></div>
                <div class="col-md-6"><h5 id="password"></h5></div>
            </div>
            <div class="row" style="margin: 10px">
                <div class="col-md-6"><h4>Email:</h4></div>
                <div class="col-md-6"><h5 id="email"></h5></div>
            </div>
            <div class="row" style="margin: 10px">
                <div class="col-md-6"><h4>Account Created:</h4></div>
                <div class="col-md-6"><h5 id="accountcreated"></h5></div>
            </div>
        </div>
        <div class="col-md-6">
        </div>
    </div>
</div>

<script src="../../../assets/js/views/account.js"></script>