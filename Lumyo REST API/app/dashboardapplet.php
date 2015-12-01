<?php namespace App;
  
use Illuminate\Database\Eloquent\Model;
  
class DashboardApplet extends Model
{
     
     protected $fillable = ['daID', 'graphID', 'daSize', 'daRow', 'loginID', 'daDeleted'];
     
}
?>