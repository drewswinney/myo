<?php namespace App;
  
use Illuminate\Database\Eloquent\Model;
  
class Session extends Model
{
     
     protected $fillable = ['sessionID', 'sessionStartTime', 'sessionEndTime', 'loginID', 'sessionQuality', 'sessionTypeID', 'sessionDeleted'];
     
}
?>