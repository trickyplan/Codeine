<?php

    function F_Date_Process($Args)
    {
        list ($day, $month, $year) = explode ('.', $Args['Value']);
        return mktime(0,0,0, $month, $day, $year);
    }