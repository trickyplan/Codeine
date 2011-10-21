<?php

    /* Codeine
     * @author BreathLess
     * @description: Ближайшая дата конца света
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 3:18
     */

    self::Fn('Nearest', function ($Call)
    {
        $Dates = array(1319173200, 1356069600, 1514786400, 1609480800, 2082780000);
        $Now = time();

        foreach ($Dates as $Date)
            if ($Now<$Date)
                return $Date;

        return null;
    });
