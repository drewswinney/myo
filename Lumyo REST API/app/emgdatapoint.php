<?php namespace App;
  
use Illuminate\Database\Eloquent\Model;
  
class EmgDataPoint extends Model
{
     
     protected $fillable = ['emgpPod1', 'emgpPod2', 'emgpPod3', 'emgpPod4', 'emgpPod5', 'emgpPod6', 'emgpPod7', 'emgpPod8', 'sessionID', 'emgpDateTime', 'emgpDeleted'];
     
}
?>