<?php
  
namespace App\Http\Controllers;
  
use App\Session;
use App\SessionType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
header("Access-Control-Allow-Origin: *");

class DashboardHelperController extends Controller
{
	public function HoursSleptPerDay($id)
	{
        $SleepSessionType = SessionType::where('stName', 'Sleep')->first();
        
        $date1 = new \DateTime('last sunday');
        $date2 = new \DateTime('last sunday +7 days');
        
        $Sessions = Session::where('loginID', $id)->where('sessionDeleted', 0)->whereRaw("sessions.sessionStartTime between '" + $date1->format('Y-m-d H:i:s') + '" and "' + $date2->format('Y-m-d H:i:s') + "'")->get();
        
        $hours = [['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'], []];
        for ($i = 0; $i < 7; $i++)
        {
            $length = 0;
            foreach ($Sessions as $Session)
            {
                $today = new \DateTime('last sunday');
                $today->setTime(0, 0);
                $today->add(new \DateInterval('P' . $i . 'D'));
                $lateToday = new \DateTime('last sunday');
                $lateToday->add(new \DateInterval('P' . $i . 'D'));
                $lateToday->setTime(23, 59);
                $sessionStartTime = new \DateTime($Session->sessionStartTime);
                $sessionEndTime = new \DateTime($Session->sessionEndTime);
                
                if($Session->sessionTypeID == $SleepSessionType->id)
                {
                    if($sessionStartTime > $today)
                    {
                        if($sessionStartTime <= $lateToday)
                        {
                            $length += $sessionEndTime->diff($sessionStartTime)->h;
                        }
                    }
                }
            }
            $hours[1][$i] = $length;
        }
  
        return response()->json($hours);
    }
  
    public function HoursFitnessPerDay($id)
    {
        $SleepSessionType = SessionType::where('stName', 'Fitness')->first();
        
        $date1 = new \DateTime('last sunday');
        $date2 = new \DateTime('last sunday +7 days');
        
        $Sessions = Session::where('loginID', $id)->where('sessionDeleted', 0)->whereRaw("sessions.sessionStartTime between '" + $date1->format('Y-m-d H:i:s') + '" and "' + $date2->format('Y-m-d H:i:s') + "'")->get();
        
        $hours = [['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'], []];
        for ($i = 0; $i < 7; $i++)
        {
            $length = 0;
            foreach ($Sessions as $Session)
            {
                $today = new \DateTime('last sunday');
                $today->setTime(0, 0);
                $today->add(new \DateInterval('P' . $i . 'D'));
                $lateToday = new \DateTime('last sunday');
                $lateToday->add(new \DateInterval('P' . $i . 'D'));
                $lateToday->setTime(23, 59);
                $sessionStartTime = new \DateTime($Session->sessionStartTime);
                $sessionEndTime = new \DateTime($Session->sessionEndTime);
                
                if($Session->sessionTypeID == $SleepSessionType->id)
                {
                    if($sessionStartTime > $today)
                    {
                        if($sessionStartTime <= $lateToday)
                        {
                            $length += $sessionEndTime->diff($sessionStartTime)->h;
                        }
                    }
                }
            }
            $hours[1][$i] = $length;
        }
  
        return response()->json($hours);
    }
  
    public function FitnessVsSleep($id)
    {
        $SleepSessionType = SessionType::where('stName', 'Sleep')->first();
        $FitnessSessionType = SessionType::where('stName', 'Fitness')->first();
        
        $date1 = new \DateTime('last sunday');
        $date2 = new \DateTime('last sunday +7 days');
        
        $Sessions = Session::where('loginID', $id)->where('sessionDeleted', 0)->whereRaw("sessions.sessionStartTime between '" + $date1->format('Y-m-d H:i:s') + '" and "' + $date2->format('Y-m-d H:i:s') + "'")->get();
        
        $fitnesshours = 0;
        $sleephours = 0;
        for ($i = 0; $i < 7; $i++)
        {
            $length = 0;
            foreach ($Sessions as $Session)
            {
                $today = new \DateTime('last sunday');
                $today->setTime(0, 0);
                $today->add(new \DateInterval('P' . $i . 'D'));
                $lateToday = new \DateTime('last sunday');
                $lateToday->add(new \DateInterval('P' . $i . 'D'));
                $lateToday->setTime(23, 59);
                $sessionStartTime = new \DateTime($Session->sessionStartTime);
                $sessionEndTime = new \DateTime($Session->sessionEndTime);

                if($sessionStartTime > $today)
                {
                    if($sessionStartTime <= $lateToday)
                    {
                        if($Session->sessionTypeID == $SleepSessionType->id)
                        {
                            $sleephours += $sessionEndTime->diff($sessionStartTime)->h;
                        }
                        elseif($Session->sessionTypeID == $FitnessSessionType->id)
                        {
                            $fitnesshours += $sessionEndTime->diff($sessionStartTime)->h;
                        }
                    }
                }
            }
        }
        
        $totalhours = $sleephours + $fitnesshours;
        $sleepratio = number_format(($sleephours / $totalhours) * 100, 0) + '%';
        $fitnessratio = number_format(($fitnesshours / $totalhours) * 100, 0) + '%';
        
        $sleep = new \stdClass;
        $sleep->label = 'Sleep';
        $sleep->value = $sleepratio;
        $fitness = new \stdClass;
        $fitness->label = 'Fitness';
        $fitness->value = $fitnessratio;
        
        $return = array($sleep, $fitness);
        
        return response()->json($return);
    }
  
    public function FitnessVsGoal($id)
    {
        $SleepSessionType = SessionType::where('stName', 'Sleep')->first();
        $FitnessSessionType = SessionType::where('stName', 'Fitness')->first();
        
        $date1 = new \DateTime('last sunday');
        $date2 = new \DateTime('last sunday +7 days');
        
        $Sessions = Session::where('loginID', $id)->where('sessionDeleted', 0)->whereRaw("sessions.sessionStartTime between '" + $date1->format('Y-m-d H:i:s') + '" and "' + $date2->format('Y-m-d H:i:s') + "'")->get();
        
        $fitnesshours = 0;
        $sleephours = 0;
        for ($i = 0; $i < 7; $i++)
        {
            foreach ($Sessions as $Session)
            {
                $today = new \DateTime('last sunday');
                $today->setTime(0, 0);
                $today->add(new \DateInterval('P' . $i . 'D'));
                $lateToday = new \DateTime('last sunday');
                $lateToday->add(new \DateInterval('P' . $i . 'D'));
                $lateToday->setTime(23, 59);
                $sessionStartTime = new \DateTime($Session->sessionStartTime);
                $sessionEndTime = new \DateTime($Session->sessionEndTime);

                if($sessionStartTime > $today)
                {
                    if($sessionStartTime <= $lateToday)
                    {
                        if($Session->sessionTypeID == $SleepSessionType->id)
                        {
                            $sleephours += $sessionEndTime->diff($sessionStartTime)->h;
                        }
                        elseif($Session->sessionTypeID == $FitnessSessionType->id)
                        {
                            $fitnesshours += $sessionEndTime->diff($sessionStartTime)->h;
                        }
                    }
                }
            }
        }
        
        $incompleteratio = number_format(((20 - $fitnesshours) / 20) * 100, 0);
        $fitnessratio = number_format(($fitnesshours / 20) * 100, 0);
        
        $incomplete = new \stdClass;
        $incomplete->label = 'Incomplete';
        $incomplete->value = $incompleteratio;
        $fitness = new \stdClass;
        $fitness->label = 'Complete';
        $fitness->value = $fitnessratio;
        
        $return = array($incomplete, $fitness);
        
        return response()->json($return);
    }
  
    public function AverageHoursSleep($id)
    {
        $SleepSessionType = SessionType::where('stName', 'Sleep')->first();
        
        $date1 = new \DateTime('last sunday');
        $date2 = new \DateTime('last sunday +7 days');
        
        $Sessions = Session::where('loginID', $id)->where('sessionDeleted', 0)->whereRaw("sessions.sessionStartTime between '" + $date1->format('Y-m-d H:i:s') + '" and "' + $date2->format('Y-m-d H:i:s') + "'")->get();
        
        $length = 0;
        for ($i = 0; $i < 7; $i++)
        {
            foreach ($Sessions as $Session)
            {
                $today = new \DateTime('last sunday');
                $today->setTime(0, 0);
                $today->add(new \DateInterval('P' . $i . 'D'));
                $lateToday = new \DateTime('last sunday');
                $lateToday->add(new \DateInterval('P' . $i . 'D'));
                $lateToday->setTime(23, 59);
                $sessionStartTime = new \DateTime($Session->sessionStartTime);
                $sessionEndTime = new \DateTime($Session->sessionEndTime);
                
                if($Session->sessionTypeID == $SleepSessionType->id)
                {
                    if($sessionStartTime > $today)
                    {
                        if($sessionStartTime <= $lateToday)
                        {
                            $length += $sessionEndTime->diff($sessionStartTime)->h;
                        }
                    }
                }
            }
        }
        
        return response()->json(number_format($length / 7, 2));
    }
    
     public function AverageHoursFitness(Request $request, $id)
    {
        $SleepSessionType = SessionType::where('stName', 'Fitness')->first();
        
        $date1 = new \DateTime('last sunday');
        $date2 = new \DateTime('last sunday +7 days');
        
        $Sessions = Session::where('loginID', $id)->where('sessionDeleted', 0)->whereRaw("sessions.sessionStartTime between '" + $date1->format('Y-m-d H:i:s') + '" and "' + $date2->format('Y-m-d H:i:s') + "'")->get();
        
        $length = 0;
        for ($i = 0; $i < 7; $i++)
        {
            foreach ($Sessions as $Session)
            {
                $today = new \DateTime('last sunday');
                $today->setTime(0, 0);
                $today->add(new \DateInterval('P' . $i . 'D'));
                $lateToday = new \DateTime('last sunday');
                $lateToday->add(new \DateInterval('P' . $i . 'D'));
                $lateToday->setTime(23, 59);
                $sessionStartTime = new \DateTime($Session->sessionStartTime);
                $sessionEndTime = new \DateTime($Session->sessionEndTime);
                
                if($Session->sessionTypeID == $SleepSessionType->id)
                {
                    if($sessionStartTime > $today)
                    {
                        if($sessionStartTime <= $lateToday)
                        {
                            $length += $sessionEndTime->diff($sessionStartTime)->h;
                        }
                    }
                }
            }
        }
        
        return response()->json(number_format($length / 7, 2));
    }
}
?>