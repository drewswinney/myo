<?php
  
namespace App\Http\Controllers;
  
use App\RotationDataPoint;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
header("Access-Control-Allow-Origin: *");
  
class RotationDatapointController extends Controller
{
	public function index()
	{
        $RotationDatapoints  = RotationDatapoint::all();
  
        return response()->json($RotationDatapoints);
    }
  
    public function getRotationDatapoint($id)
    {
        $RotationDatapoint  = RotationDatapoint::find($id);
  
        return response()->json($RotationDatapoint);
    }
  
    public function createRotationDatapoint(Request $request)
    {
        $RotationDatapoint = RotationDatapoint::create($request->all());
  
        return response()->json($RotationDatapoint);
    }
  
    public function deleteRotationDatapoint($id)
    {
        $RotationDatapoint  = RotationDatapoint::find($id);
        $RotationDatapoint->dpDeleted = 1;
        $RotationDatapoint->save();
 
        return response()->json('deleted');
    }

    public function getRotationDatapointsBySessionId($id)
    {
        $RotationDatapoints = RotationDataPoint::where('sessionID', $id)->where('rdpDeleted', 0)->whereRaw('id % 10 = 0')->get();
        return response()->json($RotationDatapoints);
    }
  
    public function updateRotationDatapoint(Request $request, $id)
    {
        $RotationDatapoint  = RotationDatapoint::find($id);
        $RotationDatapoint->rdpXRotation = $request->input('rdpXRotation');
        $RotationDatapoint->rdpYRotation = $request->input('rdpYRotation');
        $RotationDatapoint->rdpZRotation = $request->input('rdpZRotation');
        $RotationDatapoint->rdpDateTime = $request->input('rdpDateTime');
        $RotationDatapoint->sessionID = $request->input('sessionID');
        $RotationDatapoint->rdpDeleted = 0;
        $RotationDatapoint->save();
  
        return response()->json($RotationDatapoint);
    }
}
?>