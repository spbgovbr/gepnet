<?php

class App_Gantt_Calendar_CalendarSecond extends App_Gantt_Calendar_CalendarObj
{

    function int()
    {
        return $this->secondINT;
    }

    function next()
    {
        return $this->plus('1second')->second();
    }

    function prev()
    {
        return $this->minus('1second')->second();
    }

}