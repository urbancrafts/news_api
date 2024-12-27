<?php

namespace App\AppServices\Utility;


class DateTimeConfig
{
    //

    function __construct(){
    }

    public function addMinute($theMinute){
        $now = time();
        $newMinute = $now + ($theMinute * 60);
        $endDate = date('Y-m-d H:i:s', $newMinute);
        return $endDate;
    }

    public function currentTime(){
        date_default_timezone_set("Africa/Lagos");
        return date("Y-m-d H:i:s");
    }

    public function currentDate(){
        date_default_timezone_set("Africa/Lagos");
        return date("Y-m-d");
    }

    public function getDateTime($dateTime){
        return date('Y-m-d H:i:s', strtotime($dateTime));
    }

    public function addDate($dateTime){
        return date('Y-m-d H:i:s', strtotime("$dateTime", strtotime(self::currentTime())));
    }

    public function dateFrmat($cDate, $alt=null){
        if($cDate){
            list($y, $m, $d) = explode("-", $cDate);
            if(checkdate($m, $d, $y)){
                $date = date_create($cDate);
                return date_format($date," Y/m/d $alt");
            }else{
                return false;	
            }
        }
        else{
            return false;
        }
        
    }

    public function dateFrmatAlt($cDate, $alt=null){
        list($y, $m, $d) = explode("-", $cDate);

        if(checkdate($m, $d, $y)){
            $date = date_create($cDate);
            return date_format($date," M d, Y $alt");
        }else{
            return false;	
        }
    }

    //Grab years dynamically
    public function yearOptions($start, $addTo){
        $futureYears = array();
        for($i = date('Y'); $i < date('Y')+$addTo; $i++){
            $futureYears[] = $i;
        }


        $yearsArray = array();
            $yearsArray[] = date('Y');
            for($i = $start ; $i < date('Y'); $i++){
            $yearsArray[] = $i;
        }

        //
        $years = array_merge($futureYears, $yearsArray);
        rsort($years);

        return $years;	
    }

    public function secondsToTime($date) {
        $rawSeconds = strtotime($date) - strtotime(self::currentTime());

        $days = round($rawSeconds % 86400);
        $hours = round($rawSeconds % 86400);
        $minutes = round($rawSeconds % 3600);
        $seconds = round($rawSeconds % 60);

        $seconds    = round($rawSeconds % 60);
        $minutes    = round(round($rawSeconds / 60) % 60);
        $hours      = round(round(round($rawSeconds / 60) / 60) % 24);
        $days       = round(round(round(round($rawSeconds / 60) / 60) / 24) % 30);
        $weeks      = round($rawSeconds / 604800);
        $months     = round($rawSeconds / 2600640 );
        $years      = round($rawSeconds / 31207680 );

        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$rawSeconds");

        $arra = array();
        if($date > self::currentTime()){
            $arra['Days'] = $dtF->diff($dtT)->format('%a');
            $arra['Hours'] = round(round(round($rawSeconds / 60) / 60) % 24);
            $arra['Minutes'] = round(round($rawSeconds / 60) % 60);
            $arra['Seconds'] = round($rawSeconds % 60);
        }
        else{
            $arra['Days'] = 0;
            $arra['Hours'] = 0;
            $arra['Minutes'] = 0;
            $arra['Seconds'] = 0;
        }	    

        //return $days.':'. $hours.':'.$minutes.':'.$seconds;
        return $arra;
    }



    public function timeAgo($dateTime){
        if(!$dateTime){
            return false;
        }
        
        $dateTime = strtotime($dateTime);
        $cur_time   = time();
        $time_elapsed   = $cur_time - $dateTime;
        $seconds    = $time_elapsed ;
        $minutes    = round($time_elapsed / 60 );
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400 );
        $weeks      = round($time_elapsed / 604800);
        $months     = round($time_elapsed / 2600640 );
        $years      = round($time_elapsed / 31207680 );
        // Seconds
        if($seconds <= 60){
            return '<span style="color:#096;font-style:italic;">Just now</span>';
        }
        //Minutes
        else if($minutes <= 60){
            if($minutes == 1){
                return '1 minute ago';
            }
            else{
                return "$minutes minutes ago";
            }
        }
        //Hours
        else if($hours <= 24){
            if($hours == 1){
                return "an hour ago";
            }else{
                return "$hours hrs ago";
            }
        }
        //Days
        else if($days <= 7){
            if($days == 1){
                return "Yesterday";
            }else{
                return "$days days ago";
            }
        }
        //Weeks
        else if($weeks <= 4.3){
            if($weeks == 1){
                return "a week ago";
            }else{
                return "$weeks weeks ago";
            }
        }
        //Months
        else if($months <= 12){
            if($months == 1){
                return "a month ago";
            }else{
                return "$months months ago";
            }
        }
        //Years
        else{
            if($years == 1){
                return "1 year ago";
            }else{
                return "$years years ago";
            }
        }
    }

    public function timeAgoAlt($dateTime){
        $dateTime = strtotime($dateTime);
        $cur_time   = time();
        $time_elapsed   = $cur_time - $dateTime;
        $seconds    = $time_elapsed ;
        $minutes    = round($time_elapsed / 60 );
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400 );
        $weeks      = round($time_elapsed / 604800);
        $months     = round($time_elapsed / 2600640 );
        $years      = round($time_elapsed / 31207680 );
        // Seconds
        if($seconds <= 60){
            return '<span style="color:#096;font-style:italic;">now</span>';
        }
        //Minutes
        else if($minutes <= 60){
            return "$minutes m";
        }
        //Hours
        else if($hours <= 24){
            return "$hours hrs";
        }
        //Days
        else if($days <= 7){
            return "$days d";
        }
        //Weeks
        else if($weeks <= 4.3){
            return "$weeks wks";
        }
        //Months
        else if($months <= 12){
            return "$months mo";
        }
        //Years
        else{
            return "$years yr";
        }
    }

    //Calculate weekly timer
    public function getweekSartEndDate($currentTme){
        $cur_date = strtotime($currentTme); // Change to whatever date you need
        // Get the day of the week: Sunday = 0 to Saturday = 6
        $dotw = date('w', $cur_date);
        if($dotw>1){
            $pre_monday  =  $cur_date-(($dotw-1)*24*60*60);
            $next_sunday = $cur_date+((7-$dotw)*24*60*60);
        }
        else if($dotw==1){
            $pre_monday  = $cur_date;
            $next_sunday =  $cur_date+((7-$dotw)*24*60*60);
        }
        else if($dotw==0){
            $pre_monday  =$cur_date - (6*24*60*60);;
            $next_sunday = $cur_date;
        }

        //NEW: get last day of the week
        $currDatNm = date('w');
        $remainingDatNm = 6 - $currDatNm;
        $lastDateWeek = date('Y-m-d H:i:s', strtotime('+'.$remainingDatNm.' days', strtotime(date("Y-m-d H:i:s"))));

        $date_array =   array();
        $date_array['weekStart'] = date("Y-m-d h:i:s",$pre_monday);
        $date_array['weekEnd'] = $lastDateWeek;

        return $date_array;
    }


    //Calculate monthly Start and End dates
    public function getMonthSartEndDate($currentTme){
        $date_array =   array();
        $date_array['monthStart'] = date('Y-m-01 H:i:s',strtotime('this month'));
        $date_array['monthEnd'] = date('Y-m-t H:i:s',strtotime($currentTme));

        return $date_array;
    }

    //Calculate annual Start and End dates
    public function getAnnualSartEndDate($currentTme){
        $year = date('Y');
        $start = mktime(0, 0, 0, 1, 1, $year);
        $end = mktime(0, 0, 0, 12, 31, $year);
        
        $date_array =   array();
        $date_array['yearStart'] = date('Y-m-d H:i:s', $start);
        $date_array['yearEnd'] = date('Y-m-d H:i:s', $end);

        return $date_array;
    }
}
