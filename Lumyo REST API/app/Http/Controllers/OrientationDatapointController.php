<?php
  
namespace App\Http\Controllers;
  
use App\OrientationDataPoint;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
header("Access-Control-Allow-Origin: *");
  
class OrientationDatapointController extends Controller
{
	public function index()
	{
        $OrientationDatapoints  = OrientationDataPoint::all();
  
        return response()->json($OrientationDatapoints);
    }
  
    public function getOrientationDatapoint($id)
    {
        $OrientationDataPoint  = OrientationDataPoint::find($id);
  
        return response()->json($OrientationDataPoint);
    }
  
    public function createOrientationDatapoint(Request $request)
    {
        $OrientationDataPoint = OrientationDataPoint::create($request->all());
  
        return response()->json($OrientationDataPoint);
    }
  
    public function deleteOrientationDatapoint($id)
    {
        $OrientationDataPoint  = OrientationDataPoint::find($id);
        $OrientationDataPoint->dpDeleted = 1;
        $OrientationDataPoint->save();
 
        return response()->json('deleted');
    }

    public function getOrientationDatapointsBySessionId($id)
    {
        $OrientationDatapoints = OrientationDataPoint::where('sessionID', $id)->where('odpDeleted', 0)->whereRaw('id % 10 = 0')->get();
        return response()->json($OrientationDatapoints);
    }
  
    public function updateOrientationDatapoint(Request $request, $id)
    {
        $OrientationDataPoint  = OrientationDataPoint::find($id);
        $OrientationDataPoint->odpXOrientation = $request->input('odpXOrientation');
        $OrientationDataPoint->odpYOrientation = $request->input('odpYOrientation');
        $OrientationDataPoint->odpZOrientation = $request->input('odpZOrientation');
        $OrientationDataPoint->odpDateTime = $request->input('odpDateTime');
        $OrientationDataPoint->sessionID = $request->input('sessionID');
        $OrientationDataPoint->odpDeleted = 0;
        $OrientationDataPoint->save();
  
        return response()->json($OrientationDataPoint);
    }
}
?>