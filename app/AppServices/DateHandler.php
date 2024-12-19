<?php
namespace App\CampaignServices;

use DateTime;
use \stdClass;

class DateHandler {

    public static function time_diff($start, $end = NULL, $convert_to_timestamp = FALSE) {
        // If $convert_to_timestamp is not explicitly set to TRUE,
        // check to see if it was accidental:
        if ($convert_to_timestamp || !is_numeric($start)) {
          // If $convert_to_timestamp is TRUE, convert to timestamp:
          $timestamp_start = strtotime($start);
        }
        else {
          // Otherwise, leave it as a timestamp:
          $timestamp_start = $start;
        }
        // Same as above, but make sure $end has actually been overridden with a non-null,
        // non-empty, non-numeric value:
        if (!is_null($end) && (!empty($end) && !is_numeric($end))) {
          $timestamp_end = strtotime($end);
        }
        else {
          // If $end is NULL or empty and non-numeric value, assume the end time desired
          // is the current time (useful for age, etc):
          $timestamp_end = time();
        }
        // Regardless, set the start and end times to an integer:
        $start_time = (int) $timestamp_start;
        $end_time = (int) $timestamp_end;
      
        // Assign these values as the params for $then and $now:
        $start_time_var = 'start_time';
        $end_time_var = 'end_time';
        // Use this to determine if the output is positive (time passed) or negative (future):
        $pos_neg = 1;
      
        // If the end time is at a later time than the start time, do the opposite:
        if ($end_time <= $start_time) {
          $start_time_var = 'end_time';
          $end_time_var = 'start_time';
          $pos_neg = -1;
        }
      
        // Convert everything to the proper format, and do some math:
        $then = new DateTime(date('Y-m-d H:i:s', $$start_time_var));
        $now = new DateTime(date('Y-m-d H:i:s', $$end_time_var));
      
        $years_then = $then->format('Y');
        $years_now = $now->format('Y');
        $years = $years_now - $years_then;
      
        $months_then = $then->format('m');
        $months_now = $now->format('m');
        $months = $months_now - $months_then;
      
        $days_then = $then->format('d');
        $days_now = $now->format('d');
        $days = $days_now - $days_then;
      
        $hours_then = $then->format('H');
        $hours_now = $now->format('H');
        $hours = $hours_now - $hours_then;
      
        $minutes_then = $then->format('i');
        $minutes_now = $now->format('i');
        $minutes = $minutes_now - $minutes_then;
      
        $seconds_then = $then->format('s');
        $seconds_now = $now->format('s');
        $seconds = $seconds_now - $seconds_then;
      
        if ($seconds < 0) {
          $minutes -= 1;
          $seconds += 60;
        }
        if ($minutes < 0) {
          $hours -= 1;
          $minutes += 60;
        }
        if ($hours < 0) {
          $days -= 1;
          $hours += 24;
        }
        $months_last = $months_now - 1;
        if ($months_now == 1) {
          $years_now -= 1;
          $months_last = 12;
        }
      
        // "Thirty days hath September, April, June, and November" ;)
        if ($months_last == 9 || $months_last == 4 || $months_last == 6 || $months_last == 11) {
          $days_last_month = 30;
        }
        else if ($months_last == 2) {
          // Factor in leap years:
          if (($years_now % 4) == 0) {
            $days_last_month = 29;
          }
          else {
            $days_last_month = 28;
          }
        }
        else {
          $days_last_month = 31;
        }
        if ($days < 0) {
          $months -= 1;
          $days += $days_last_month;
        }
        if ($months < 0) {
          $years -= 1;
          $months += 12;
        }
      
        // Finally, multiply each value by either 1 (in which case it will stay the same),
        // or by -1 (in which case it will become negative, for future dates).
        // Note: 0 * 1 == 0 * -1 == 0
        $out = new stdClass;
        $out->years = (int) $years * $pos_neg;
        $out->months = (int) $months * $pos_neg;
        $out->days = (int) $days * $pos_neg;
        $out->hours = (int) $hours * $pos_neg;
        $out->minutes = (int) $minutes * $pos_neg;
        $out->seconds = (int) $seconds * $pos_neg;
        return $out;
      }
      
}