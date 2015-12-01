<?php
  
namespace App\Http\Controllers;
  
use App\DashboardGraphType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
header("Access-Control-Allow-Origin: *");

class DashboardGraphTypeController extends Controller
{
	public function index()
	{
        $DashboardGraphTypes  = DashboardGraphType::all();
  
        return response()->json($DashboardGraphTypes);
    }
  
    public function getDashboardGraphType($id)
    {
        $DashboardGraphType  = DashboardGraphType::find($id);
  
        return response()->json($DashboardGraphType);
    }
  
    public function createDashboardGraphType(Request $request)
    {
        $DashboardGraphType = DashboardGraphType::create($request->all());
  
        return response()->json($DashboardGraphType);
    }
  
    public function deleteDashboardGraphType($id)
    {
        $DashboardGraphType  = DashboardGraphType::find($id);
        $DashboardGraphType->dgtDeleted = 1;
        $DashboardGraphType->save();
 
        return response()->json('deleted');
    }
  
    public function updateDashboardGraphType(Request $request, $id)
    {
        $DashboardGraphType  = DashboardGraphType::find($id);
        $DashboardGraphType->dgSize = $request->input('dgtName');
        $DashboardGraphType->dgtSubType = $request->input('dgtSubType');
        $DashboardGraphType->dgDeleted = 0;
        $DashboardGraphType->save();
  
        return response()->json($DashboardGraphType);
    }
}
?>