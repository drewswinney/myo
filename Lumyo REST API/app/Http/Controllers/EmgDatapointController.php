<?php
  
namespace App\Http\Controllers;
  
use Log;
use App\EmgDataPoint;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
header("Access-Control-Allow-Origin: *");
  
class EmgDatapointController extends Controller
{
	public function index()
	{
        $EmgDatapoints  = EmgDatapoint::all();
  
        return response()->json($EmgDatapoints);
    }
  
    public function getEmgDatapoint($id)
    {
        $EmgDatapoint  = EmgDatapoint::find($id);
  
        return response()->json($EmgDatapoint);
    }
  
    public function createEmgDatapoint(Request $request)
    {
        $EmgDatapoint = EmgDatapoint::create($request->all());
  
        return response()->json($EmgDatapoint);
    }
  
    public function deleteEmgDatapoint($id)
    {
        $EmgDatapoint  = EmgDatapoint::find($id);
        $EmgDatapoint->emgpDeleted = 1;
        $EmgDatapoint->save();
 
        return response()->json('deleted');
    }

    public function getEmgDatapointsBySessionId($id)
    {
        //DB::raw();
        $EmgDataPoints = EmgDataPoint::where('sessionID', $id)->where('emgpDeleted', 0)->whereRaw('id % 10 = 0')->orderBy('emgpDateTime', 'asc')->get();
        return response()->json($EmgDataPoints);
    }
  
    public function updateEmgDatapoint(Request $request, $id)
    {
        $EmgDatapoint  = EmgDatapoint::find($id);
        $EmgDatapoint->emgpPod1 = $request->input('emgpPod1');
        $EmgDatapoint->emgpPod2 = $request->input('emgpPod2');
        $EmgDatapoint->emgpPod3 = $request->input('emgpPod3');
        $EmgDatapoint->emgpPod1 = $request->input('emgpPod4');
        $EmgDatapoint->emgpPod2 = $request->input('emgpPod5');
        $EmgDatapoint->emgpPod3 = $request->input('emgpPod6');
        $EmgDatapoint->emgpPod1 = $request->input('emgpPod7');
        $EmgDatapoint->emgpPod2 = $request->input('emgpPod8');
        $EmgDatapoint->emgpDateTime = $request->input('emgpDateTime');
        $EmgDatapoint->sessionID = $request->input('sessionID');
        $EmgDatapoint->emgpDeleted = 0;
        $EmgDatapoint->save();
  
        return response()->json($EmgDatapoint);
    }
}
?>