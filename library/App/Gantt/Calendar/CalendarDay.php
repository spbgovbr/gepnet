<?php

class App_Gantt_Calendar_CalendarDay extends App_Gantt_Calendar_CalendarObj
{

    function __toString()
    {
        return (string)$this->format('Y-m-d');
    }

    function int()
    {
        return $this->dayINT;
    }

    function week()
    {
        $week = date('W', $this->timestamp);
        $year = ($this->monthINT == 1 && $week > 5) ? $this->year()->prev() : $this->year();
        return new App_Gantt_Calendar_CalendarWeek($year->int(), $week);
    }

    function next()
    {
        return $this->plus('1day');
    }

    function prev()
    {
        return $this->minus('1day');
    }

    function weekday()
    {
        return date('N', $this->timestamp);
    }

    function name()
    {
        return strftime('%A', $this->timestamp);
    }

    function shortname()
    {
        return strftime('%a', $this->timestamp);
    }

    function isToday()
    {
        $cal = new App_Gantt_Calendar_Calendar();
        return $this == $cal->today();
    }

    function isYesterday()
    {
        $cal = new App_Gantt_Calendar_Calendar();
        return $this == $cal->yesterday();
    }

    function isTomorrow()
    {
        $cal = new App_Gantt_Calendar_Calendar();
        return $this == $cal->tomorrow();
    }

    function isInThePast()
    {
        return ($this->timestamp < App_Gantt_Calendar_Calendar::$now) ? true : false;
    }

    function isInTheFuture()
    {
        return ($this->timestamp > App_Gantt_Calendar_Calendar::$now) ? true : false;
    }

    function isWeekend()
    {
        $num = $this->format('w');
        return ($num == 6 || $num == 0) ? true : false;
    }

    function hours()
    {

        $obj = $this;
        $array = array();

        while ($obj->int() == $this->int()) {
            $array[] = $obj->hour();
            $obj = $obj->plus('1hour');
        }

        return new App_Gantt_Calendar_CalendarIterator($array);

    }

}