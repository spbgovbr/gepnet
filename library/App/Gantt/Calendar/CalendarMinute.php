<?php

class App_Gantt_Calendar_CalendarMinute extends App_Gantt_Calendar_CalendarObj
{

    function int()
    {
        return $this->minuteINT;
    }

    function seconds()
    {

        $obj = $this;
        $array = array();

        while ($obj->minuteINT == $this->minuteINT) {
            $array[] = $obj;
            $obj = $obj->plus('1second')->second();
        }

        return new App_Gantt_Calendar_CalendarIterator($array);

    }

    function next()
    {
        return $this->plus('1minute')->minute();
    }

    function prev()
    {
        return $this->minus('1minute')->minute();
    }

}