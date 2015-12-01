<?php namespace App;
  
use Illuminate\Database\Eloquent\Model;
  
class RotationDataPoint extends Model
{
     
     protected $fillable = ['rdpXRotation', 'rdpYRotation', 'rdpZRotation', 'sessionID', 'rdpDateTime', 'dpDeleted'];
     
}
?>