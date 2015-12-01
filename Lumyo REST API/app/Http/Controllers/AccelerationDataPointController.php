<?php
  
namespace App\Http\Controllers;
  
use App\AccelerationDataPoint;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
header("Access-Control-Allow-Origin: *");
  
class AccelerationDatapointController extends Controller
{
	public function index()
	{
        $AccelerationDatapoints  = AccelerationDataPoint::all();
  
        return response()->json($AccelerationDatapoints);
    }
  
    public function getAccelerationDatapoint($id)
    {
        $AccelerationDatapoint  = AccelerationDataPoint::find($id);
  
        return response()->json($AccelerationDatapoint);
    }
  
    public function createAccelerationDatapoint(Request $request)
    {
        $AccelerationDatapoint = AccelerationDataPoint::create($request->all());
  
        return response()->json($AccelerationDatapoint);
    }
  
    public function deleteAccelerationDatapoint($id)
    {
        $AccelerationDatapoint  = AccelerationDataPoint::find($id);
        $AccelerationDatapoint->dpDeleted = 1;
        $AccelerationDatapoint->save();
 
        return response()->json('deleted');
    }

    public function getAccelerationDatapointsBySessionId($id)
    {
        $AccelerationDatapoints = AccelerationDataPoint::where('sessionID', $id)->where('adpDeleted', 0)->whereRaw('id % 10 = 0')->orderBy('adpDateTime', 'asc')->get();
        return response()->json($AccelerationDatapoints);
    }
  
    public function updateAccelerationDatapoint(Request $request, $id)
    {
        $AccelerationDatapoint  = AccelerationDataPoint::find($id);
        $AccelerationDatapoint->adpXAcceleration = $request->input('adpXAcceleration');
        $AccelerationDatapoint->adpYAcceleration = $request->input('adpYAcceleration');
        $AccelerationDatapoint->adpZAcceleration = $request->input('adpZAcceleration');
        $AccelerationDatapoint->adpDateTime = $request->input('adpDateTime');
        $AccelerationDatapoint->sessionID = $request->input('sessionID');
        $AccelerationDatapoint->adpDeleted = 0;
        $AccelerationDatapoint->save();
  
        return response()->json($AccelerationDatapoint);
    }
}
?>