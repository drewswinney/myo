<?php 
 header("Access-Control-Allow-Origin: myo.local");
?>

<!DOCTYPE html>
<html lang="en" ng-app="MyoSleepApp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Lumyo</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/views/index.css" rel="stylesheet">
  </head>

  <body>
    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="loginmodal-container">
          <h1>Login to Your Account</h1><br>
          <input type="text" id="userText" placeholder="Username">
          <input type="password" id="passText" placeholder="Password">
          <input type="submit" id="loginSubmitBtn" class="login loginmodal-submit" value="Login">
          <div class="login-help">
            <div id="wrongPasswordAlert" class="alert alert-danger hidden text-center" role="alert">Woops! Incorrect username or password</div>
          </div>
          <!-- <a href="#">Register</a> - <a href="#">Forgot Password</a> -->
        </div>
      </div>
    </div>


    <!-- Navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a id="brand" class="navbar-brand" href="#/dashboard">Lumyo</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li id="dashboardTab" class="active"><a href="#/dashboard">Dashboard</a></li>
            <li id="sleepdataTab"><a href="#/sleepdata">Session Data</a></li>      
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span></a>
              <ul class="dropdown-menu">
                <li><a id="accountTab" href="#/account">Account</a></li>
                <li role="separator" class="divider"></li>
                <li><a id="logoutBtn">Log Out</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container" style="margin-top:40px;">
      <div ng-view></div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/angular.min.js"></script>
    <script src="assets/js/angular-route.js"></script>
    <script src="assets/js/d3.min.js"></script>
    <script src="app/app.js"></script>
    <script src="assets/js/views/index.js"></script>
  </body>
</html>

<script>
  options = { backdrop:'static', keyboard: false, show: true };

  $('#login-modal').modal(options);
</script>