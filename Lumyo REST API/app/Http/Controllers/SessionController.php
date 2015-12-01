<?php
  
namespace App\Http\Controllers;
  
use App\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
header("Access-Control-Allow-Origin: *");
  
class SessionController extends Controller
{
	public function index()
	{
        $Sessions  = Session::all();
  
        return response()->json($Sessions);
    }
  
    public function getSession($id)
    {
        $Session  = Session::find($id);
  
        return response()->json($Session);
    }

    public function getSessionsByLoginID(Request $request, $id)
    {
        $Sessions = Session::where('loginID', $id)
        ->where('sessionDeleted', 0)
        ->whereBetween('sessionStartTime', [$request->input('sessionStartTime'), $request->input('sessionEndTime')])->get();
  
        return response()->json($Sessions);
    }
  
    public function createSession(Request $request)
    {
        $Session = Session::create($request->all());
  
        return response()->json($Session);
    }
  
    public function deleteSession($id)
    {
        $Session  = Session::find($id);
        $Session->sessionDeleted = 1;
        $Session->save();
 
        return response()->json('deleted');
    }
  
    public function updateSession(Request $request, $id)
    {
        $Session  = Session::find($id);
        $Session->sessionStartTime = $request->input('sessionStartTime');
        $Session->sessionEndTime = $request->input('sessionEndTime');
        $Session->loginID = $request->input('loginID');
        $Session->sessionQuality = $request->input('sessionQuality');
        $Session->sessionTypeID = $request->input('sessionTypeID');
        $Session->sessionDeleted = 0;
        $Session->save();
  
        return response()->json($Session);
    }
}
?>