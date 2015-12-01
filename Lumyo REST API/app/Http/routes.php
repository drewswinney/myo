<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->welcome();
});

$app->group(['prefix' => 'api','namespace' => 'App\Http\Controllers'], function($app)
{
    $app->get('login','LoginController@index');
  
    $app->get('login/{id}','LoginController@getLogin');
      
    $app->post('login','LoginController@createLogin');
      
    $app->put('login/{id}','LoginController@updateLogin');
      
    $app->delete('login/{id}','LoginController@deleteLogin');

    $app->post('loginauth','LoginController@authenticateLogin');


    //Session
    $app->get('session','SessionController@index');
  
    $app->get('session/{id}','SessionController@getSession');
      
    $app->post('session','SessionController@createSession');
      
    $app->put('session/{id}','SessionController@updateSession');
      
    $app->delete('session/{id}','SessionController@deleteSession');

    $app->get('sessionsbyloginid/{id}','SessionController@getSessionsByLoginID');
    
     //Session
    $app->get('sessiontype','SessionTypeController@index');
  
    $app->get('sessiontype/{id}','SessionTypeController@getSessionType');
      
    $app->post('sessiontype','SessionTypeController@createSessionType');
      
    $app->put('sessiontype/{id}','SessionTypeController@updateSessionType');
      
    $app->delete('sessiontype/{id}','SessionTypeController@deleteSessionType');


    //RotationDataPoint
    $app->get('rotationdatapoint','RotationDatapointController@index');
  
    $app->get('rotationdatapoint/{id}','RotationDatapointController@getRotationDatapoint');
      
    $app->post('rotationdatapoint','RotationDatapointController@createRotationDatapoint');
      
    $app->put('rotationdatapoint/{id}','RotationDatapointController@updateRotationDatapoint');
      
    $app->delete('rotationdatapoint/{id}','RotationDatapointController@deleteRotationDatapoint');

    $app->get('rotationdatapointsbysessionid/{id}', 'RotationDatapointController@getRotationDatapointsBySessionId');


    //AccelerationDataPoint
    $app->get('accelerationdatapoint','AccelerationDatapointController@index');
  
    $app->get('accelerationdatapoint/{id}','AccelerationDatapointController@getAccelerationDatapoint');
      
    $app->post('accelerationdatapoint','AccelerationDatapointController@createAccelerationDatapoint');
      
    $app->put('accelerationdatapoint/{id}','AccelerationDatapointController@updateAccelerationDatapoint');
      
    $app->delete('accelerationdatapoint/{id}','AccelerationDatapointController@deleteAccelerationDatapoint');

    $app->get('accelerationdatapointsbysessionid/{id}', 'AccelerationDatapointController@getAccelerationDatapointsBySessionId');

    //EmgDataPoint
    $app->get('emgdatapoint','EmgDatapointController@index');
  
    $app->get('emgdatapoint/{id}','EmgDatapointController@getEmgDatapoint');
      
    $app->post('emgdatapoint','EmgDatapointController@createEmgDatapoint');
      
    $app->put('emgdatapoint/{id}','EmgDatapointController@updateEmgDatapoint');
      
    $app->delete('emgdatapoint/{id}','EmgDatapointController@deleteEmgDatapoint');

    $app->get('emgdatapointsbysessionid/{id}', 'EmgDatapointController@getEmgDatapointsBySessionId');

      //EmgDataPoint
    $app->get('orientationdatapoint','OrientationDatapointController@index');
  
    $app->get('orientationdatapoint/{id}','OrientationDatapointController@getOrientationDatapoint');
      
    $app->post('orientationdatapoint','OrientationDatapointController@createOrientationDatapoint');
      
    $app->put('orientationdatapoint/{id}','OrientationDatapointController@updateOrientationDatapoint');
      
    $app->delete('orientationdatapoint/{id}','OrientationDatapointController@deleteOrientationDatapoint');

    $app->get('orientationdatapointsbysessionid/{id}', 'OrientationDatapointController@getOrientationDatapointsBySessionId');

    //DashboardGraph
    $app->get('dashboardgraph','DashboardGraphController@index');
  
    $app->get('dashboardgraph/{id}','DashboardGraphController@getDashboardGraph');
      
    $app->post('dashboardgraph','DashboardGraphController@createDashboardGraph');
      
    $app->put('dashboardgraph/{id}','DashboardGraphController@updateDashboardGraph');
      
    $app->delete('dashboardgraph/{id}','DashboardGraphController@deleteDashboardGraph');

    $app->get('dashboardgraphbyloginid/{id}','DashboardGraphController@getDashboardGraphByLoginId');
    
    //DashboardGraphType
    $app->get('dashboardgraphtype','DashboardGraphTypeController@index');
  
    $app->get('dashboardgraphtype/{id}','DashboardGraphTypeController@getDashboardGraphType');
      
    $app->post('dashboardgraphtype','DashboardGraphTypeController@createDashboardGraphType');
    
    $app->put('dashboardgraphtype/{id}','DashboardGraphTypeController@updateDashboardGraphType');
      
    $app->delete('dashboardgraphtype/{id}','DashboardGraphTypeController@deleteDashboardGraphType');
    
    
    $app->get('dashboardhelper/hourssleptperday/{id}','DashboardHelperController@HoursSleptPerDay');
  
    $app->get('dashboardhelper/hoursfitnessperday/{id}','DashboardHelperController@HoursFitnessPerDay');
      
    $app->get('dashboardhelper/fitnessvssleep/{id}','DashboardHelperController@FitnessVsSleep');
    
    $app->get('dashboardhelper/fitnessvsgoal/{id}','DashboardHelperController@FitnessVsGoal');
      
    $app->get('dashboardhelper/averagehourssleep/{id}','DashboardHelperController@AverageHoursSleep');
    
    $app->get('dashboardhelper/averagehoursfitness/{id}','DashboardHelperController@AverageHoursFitness');
});