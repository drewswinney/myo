<?php namespace App;
  
use Illuminate\Database\Eloquent\Model;
  
class AccelerationDataPoint extends Model
{
     
     protected $fillable = ['adpXAcceleration', 'adpYAcceleration', 'adpZAcceleration', 'sessionID', 'adpDateTime', 'adpDeleted'];
     
}
?>