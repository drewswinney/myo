<?php
  
namespace App\Http\Controllers;
  
use App\DashboardApplet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
header("Access-Control-Allow-Origin: *");
  
class DashboardAppletController extends Controller
{
	public function index()
	{
        $DashboardApplets  = DashboardApplet::all();
  
        return response()->json($DashboardApplets);
    }
  
    public function getDashboardApplet($id)
    {
        $DashboardApplet  = DashboardApplet::find($id);
  
        return response()->json($DashboardApplet);
    }

    public function getDashboardAppletByLoginId($id)
    {
        $DashboardApplets  = DashboardApplet::where('loginID', $id)->where('daDeleted', 0)->get();
  
        return response()->json($DashboardApplets);
    }
  
    public function createDashboardApplet(Request $request)
    {
        $DashboardApplet = DashboardApplet::create($request->all());
  
        return response()->json($DashboardApplet);
    }
  
    public function deleteDashboardApplet($id)
    {
        $DashboardApplet  = DashboardApplet::find($id);
        $DashboardApplet->daDeleted = 1;
        $DashboardApplet->save();
 
        return response()->json('deleted');
    }
  
    public function updateDashboardApplet(Request $request, $id)
    {
        $DashboardApplet  = DashboardApplet::find($id);
        $DashboardApplet->daSize = $request->input('daSize');
        $DashboardApplet->daRow = $request->input('daRow');
        $DashboardApplet->graphID = $request->input('graphID');
        $DashboardApplet->loginID = $request->input('loginID');
        $DashboardApplet->daDeleted = 0;
        $DashboardApplet->save();
  
        return response()->json($DashboardApplet);
    }
}
?>