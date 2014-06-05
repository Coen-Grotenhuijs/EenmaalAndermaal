<?php

class Timer
{
        public static $js;
        
        public static $number;
        
        public function __construct()
        {
                if(empty(self::$js))
                {
                        self::$js = '';
                }
                
                if(empty(self::$number))
                {
                        self::$number = $_SESSION['timer_counter'];
                }
        }
        
        public function setTimer($time)
        {
                self::$number++;
                self::$js .= "$('#timer".self::$number."').countdown({until: +".($time-time()).", compact: true, layout: '{dn}d {hn}u {mn}m {sn}s'});
                        ";
                return 'timer'.self::$number;
        }
        
        
        public function getJavascript()
        {
                return self::$js;
        }
        
        public function getTimestamp($date, $time)
        {
                $date = str_replace("/","-", $date);
                $temp_date = explode("-", $date);
                $temp_time = explode(":",$time);
                $timestamp = mktime($temp_time[0], $temp_time[1], 0, $temp_date[1], $temp_date[0], $temp_date[2]);
                return $timestamp;
        }
        
        public static function resetCounter()
        {
                $_SESSION['timer_counter'] = 0;
        }
        
        public function __destruct()
        {
                $_SESSION['timer_counter'] = self::$number;
        }
}


?>