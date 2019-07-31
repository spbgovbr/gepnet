<?php

class App_Gantt_Calendar_CalendarWeek extends App_Gantt_Calendar_CalendarObj
{

    function __toString()
    {
        return $this->firstDay()->format('Y-m-d') . ' - ' . $this->lastDay()->format('Y-m-d');
    }

    var $weekINT;

    function int()
    {
        return $this->weekINT;
    }

    function __construct($year = false, $week = false)
    {

        if (!$year) {
            $year = date('Y');
        }
        if (!$week) {
            $week = date('W');
        }

        $this->yearINT = intval($year);
        $this->weekINT = intval($week);

        $ts = strtotime('Thursday', strtotime($year . 'W' . $this->padded()));
        $monday = strtotime('-3days', $ts);

        parent::__construct(date('Y', $monday), date('m', $monday), date('d', $monday), 0, 0, 0);

    }

    function years()
    {
        $array = array();
        $array[] = $this->firstDay()->year();
        $array[] = $this->lastDay()->year();

        // remove duplicates
        $array = array_unique($array);

        return new App_Gantt_Calendar_CalendarIterator($array);
    }

    function months()
    {
        $array = array();
        $array[] = $this->firstDay()->month();
        $array[] = $this->lastDay()->month();

        // remove duplicates
        $array = array_unique($array);

        return new App_Gantt_Calendar_CalendarIterator($array);
    }

    function firstDay()
    {
        $cal = new App_Gantt_Calendar_Calendar();
        return $cal->date($this->timestamp);
    }

    function lastDay()
    {
        $first = $this->firstDay();
        return $first->plus('6 days');
    }

    function days()
    {

        $day = $this->firstDay();
        $array = array();

        for ($x = 0; $x < 7; $x++) {
            $array[] = $day;
            $day = $day->next();
        }

        return new App_Gantt_Calendar_CalendarIterator($array);

    }

    function next()
    {

        $next = strtotime('Thursday next week', $this->timestamp);
        $year = date('Y', $next);
        $week = date('W', $next);

        return new App_Gantt_Calendar_CalendarWeek($year, $week);

    }

    function prev()
    {

        $prev = strtotime('Monday last week', $this->timestamp);
        $year = date('Y', $prev);
        $week = date('W', $prev);

        return new App_Gantt_Calendar_CalendarWeek($year, $week);

    }

}