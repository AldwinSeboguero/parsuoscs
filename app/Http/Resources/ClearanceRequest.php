<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DateTime;
class ClearanceRequest extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { 
        $timestamp1 = $this->requested_at ? $this->requested_at->format('Y-m-d H:i:s') : null;
        $timestamp2 = $this->approved_at ? $this->approved_at->format('Y-m-d H:i:s') : null;

        $datetime1 = new DateTime($timestamp1);
        $datetime2 = new DateTime($timestamp2);

        $working_hours_morning_start = 8;
        $working_hours_morning_end = 12;
        $working_hours_afternoon_start = 13;
        $working_hours_afternoon_end = 17;
        
        $holidays = [
            '2022-02-21',
            '2022-02-24',
        ];
        
        $interval = $datetime1->diff($datetime2);
        $working_hours = 0;
        $working_days = 0;
        
        // Loop through each day in the interval
        for ($i = 0; $i < $interval->days; $i++) {
            $current_date = clone $datetime1;
            $current_date->modify("+$i day");
        
            // If the current date is a weekend or holiday, skip it
            if ($current_date->format('N') >= 6 || in_array($current_date->format('Y-m-d'), $holidays)) {
                continue;
            }
        
            // Calculate the working hours for the current day
            $day_working_hours = 0;
        
            // If the current date is the first day, adjust the working hours
            if ($i == 0) {
                $start_time = $datetime1->format('H');
                if ($start_time >= $working_hours_morning_start && $start_time < $working_hours_morning_end) {
                    $day_working_hours += $working_hours_morning_end - $start_time;
                } elseif ($start_time >= $working_hours_afternoon_start && $start_time < $working_hours_afternoon_end) {
                    $day_working_hours += $working_hours_afternoon_end - $start_time;
                }
            } else {
                $day_working_hours += ($working_hours_morning_end - $working_hours_morning_start) + ($working_hours_afternoon_end - $working_hours_afternoon_start);
            }
        
            $working_hours += $day_working_hours;
            $working_days++;
        }
        
        // Output the result in hours and minutes along with the number of working days
        $hours = floor($working_hours);
        $minutes = round(($working_hours - $hours) * 60);
        return [
            
            'id' => $this->id,
            'token' => $this->token,
            'name' => $this->student->name,
            'student_number' => $this->student->student_number,
            'program' => $this->student->program->short_name,
            'staff' => $this->signatory ? $this->signatory->name : '',
            // 'deficiencies' => $this->student,
            // 'approved_at' => $this->approved_at ? $this->approved_at->format('M d, Y g:i A') : null,
            'approved_at' => $this->approved_at ? $this->approved_at->format('M d, Y g:i A') : null,
            'request_at' => $this->requested_at ? $this->requested_at->format('M d, Y g:i A') : null,
            'purpose' => json_decode(json_decode($this->purpose)->purpose)->name.' '.
            json_decode(json_decode($this->purpose)->purpose)->description, 
            'interval' => "Working hours interval: $hours hours and $minutes minutes, $working_days working days",
            
        ];
     
    }
}
