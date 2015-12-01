<?php namespace App;
  
use Illuminate\Database\Eloquent\Model;
  
class DashboardGraphType extends Model
{
     
     protected $fillable = ['id', 'dgtName', 'dgtSubType', 'dgtDeleted'];
     
}
?>