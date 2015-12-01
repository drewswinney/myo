<?php
  
namespace App\Http\Controllers;
  
use App\SessionType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
header("Access-Control-Allow-Origin: *");
  
class SessionTypeController extends Controller
{
	public function index()
	{
        $SessionTypes = SessionType::all();
  
        return response()->json($SessionTypes);
    }
  
    public function getSessionType($id)
    {
        $SessionType  = SessionType::find($id);
  
        return response()->json($SessionType);
    }
  
    public function createSessionType(Request $request)
    {
        $SessionType = SessionType::create($request->all());
  
        return response()->json($SessionType);
    }
  
    public function deleteSessionType($id)
    {
        $SessionType  = SessionType::find($id);
        $SessionType->stDeleted = 1;
        $SessionType->save();
 
        return response()->json('deleted');
    }
  
    public function updateSessionType(Request $request, $id)
    {
        $SessionType  = SessionType::find($id);
        $SessionType->stID = $request->input('stID');
        $SessionType->stName = $request->input('stName');
        $SessionType->stDeleted = 0;
        $SessionType->save();
  
        return response()->json($SessionType);
    }
}
?>