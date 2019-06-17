<?php

class App_Gantt_Calendar_CalendarHour extends App_Gantt_Calendar_CalendarObj
{

    function int()
    {
        return $this->hourINT;
    }

    function minutes()
    {

        $obj = $this;
        $array = array();

        while ($obj->hourINT == $this->hourINT) {
            $array[] = $obj;
            $obj = $obj->plus('1minute')->minute();
        }

        return new App_Gantt_Calendar_CalendarIterator($array);

    }

    function next()
    {
        return $this->plus('1hour')->hour();
    }

    function prev()
    {
        return $this->minus('1hour')->hour();
    }

}