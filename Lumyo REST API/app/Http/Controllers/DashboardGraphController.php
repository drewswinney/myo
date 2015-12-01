<?php
  
namespace App\Http\Controllers;
  
use App\DashboardGraph;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
header("Access-Control-Allow-Origin: *");
  
class DashboardGraphController extends Controller
{
	public function index()
	{
        $DashboardGraphs  = DashboardGraph::all();
  
        return response()->json($DashboardGraphs);
    }
  
    public function getDashboardGraph($id)
    {
        $DashboardGraph  = DashboardGraph::find($id);
  
        return response()->json($DashboardGraph);
    }

    public function getDashboardGraphByLoginId($id)
    {
        $DashboardGraphs  = DashboardGraph::where('loginID', $id)->where('dgDeleted', 0)->get();
  
        return response()->json($DashboardGraphs);
    }
  
    public function createDashboardGraph(Request $request)
    {
        $DashboardGraph = DashboardGraph::create($request->all());
  
        return response()->json($DashboardGraph);
    }
  
    public function deleteDashboardGraph($id)
    {
        $DashboardGraph  = DashboardGraph::find($id);
        $DashboardGraph->dgDeleted = 1;
        $DashboardGraph->save();
 
        return response()->json('deleted');
    }
  
    public function updateDashboardGraph(Request $request, $id)
    {
        $DashboardGraph  = DashboardGraph::find($id);
        $DashboardGraph->dgSize = $request->input('dgSize');
        $DashboardGraph->dgRow = $request->input('dgRow');
        $DashboardGraph->dgtID = $request->input('dgtID');
        $DashboardGraph->loginID = $request->input('loginID');
        $DashboardGraph->dgDeleted = 0;
        $DashboardGraph->save();
  
        return response()->json($DashboardGraph);
    }
}
?>