<?php

class App_Gantt_Calendar_CalendarMonth extends App_Gantt_Calendar_CalendarObj
{

    function __toString()
    {
        return (string)$this->format('Y-m');
    }

    function int()
    {
        return $this->monthINT;
    }

    function weeks($force = false)
    {

        $first = $this->firstDay();
        $week = $first->week();

        $currentMonth = $this->int();
        $nextMonth = $this->next()->int();

        $max = ($force) ? $force : 6;

        for ($x = 0; $x < $max; $x++) {

            // make sure not to add weeks without a single day in the same month
            if (!$force && $x > 0 && $week->firstDay()->month()->int() != $currentMonth) {
                break;
            }

            $array[] = $week;

            // make sure not to add weeks without a single day in the same month
            if (!$force && $week->lastDay()->month()->int() != $currentMonth) {
                break;
            }

            $week = $week->next();

        }

        return new App_Gantt_Calendar_CalendarIterator($array);

    }

    function countDays()
    {
        return date('t', $this->timestamp);
    }

    function firstDay()
    {
        return new App_Gantt_Calendar_CalendarDay($this->yearINT, $this->monthINT, 1);
    }

    function lastDay()
    {
        return new App_Gantt_Calendar_CalendarDay($this->yearINT, $this->monthINT, $this->countDays());
    }

    function days()
    {

        // number of days per month
        $days = date('t', $this->timestamp);
        $array = array();
        $ts = $this->firstDay()->timestamp();

        foreach (range(1, $days) as $day) {
            $array[] = $this->day($day);
        }

        return new App_Gantt_Calendar_CalendarIterator($array);

    }

    function day($day = 1)
    {
        return new App_Gantt_Calendar_CalendarDay($this->yearINT, $this->monthINT, $day);
    }

    function next()
    {
        return $this->plus('1month')->month();
    }

    function prev()
    {
        return $this->minus('1month')->month();
    }

    function name()
    {
        switch (strftime('%B', $this->timestamp)) {
            case 'January':
                return 'Janeiro';
                break;
            case 'February':
                return 'Fevereiro';
                break;
            case 'March':
                return 'Março';
                break;
            case 'April':
                return 'Abril';
                break;
            case 'May':
                return 'Maio';
                break;
            case 'June':
                return 'Junho';
                break;
            case 'July':
                return 'Julho';
                break;
            case 'August':
                return 'Agosto';
                break;
            case 'September':
                return 'Setembro';
                break;
            case 'October':
                return 'Outubro';
                break;
            case 'November':
                return 'Novembro';
                break;
            case 'December':
                return 'Dezembro';
                break;

            default:
                return strftime('%B', $this->timestamp);
                break;
        }
    }

    function shortname()
    {
        return strftime('%b', $this->timestamp);
    }

}