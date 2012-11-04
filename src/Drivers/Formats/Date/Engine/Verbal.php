<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.x
     */

    setFn('Format', function ($Call)
    {
        $Difference = $Call['Value']-time();
        $Direction = ($Difference>0);

        $Difference  = abs($Difference);

        $Output = '';

        $Ranges = array('Year' => 31536000, 'Month' => 2419200, 'Day' => 86400);

        $Slices = array();

        foreach ($Ranges as $Range => $Seconds)
            if ($Difference > $Seconds)
            {
                $Slices[$Range] = floor($Difference / $Seconds);
                $Difference = $Difference % $Seconds;
            }

        foreach ($Slices as $Range => $Slice)
            $Output.= ' '.$Slice.' <l>'.$Range.'.'.($Slice%10).'.</l> ';

        return  ($Direction? '<l>Pre.Forward</l>': '<l>Pre.Ago</l>').$Output.($Direction? '<l>Post.Forward</l>':
                '<l>Post.Ago</l>');
     });