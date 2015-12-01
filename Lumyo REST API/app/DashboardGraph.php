<?php namespace App;
  
use Illuminate\Database\Eloquent\Model;
  
class DashboardGraph extends Model
{
     
     protected $fillable = ['id', 'dgSize', 'dgRow', 'dgtID', 'loginID', 'dgDeleted'];
     
}
?>