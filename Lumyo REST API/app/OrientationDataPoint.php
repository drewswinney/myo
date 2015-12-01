<?php namespace App;
  
use Illuminate\Database\Eloquent\Model;
  
class OrientationDataPoint extends Model
{
     
     protected $fillable = ['odpXOrientation', 'odpYOrientation', 'odpZOrientation', 'sessionID', 'odpDateTime', 'odpDeleted'];
     
}
?>