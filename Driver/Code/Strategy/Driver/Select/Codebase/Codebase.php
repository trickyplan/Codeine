<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Codebase size analyzing strategy
     * @package Codeine
     * @subpackage Drivers
     * @version 0
     * @date 24.11.10
     * @time 2:43
     */

    self::Fn('Select', function ($Call)
    {
        $Sizes = array();

        foreach ($Call['Drivers'] as $Driver)
            $Sizes[$Driver] = filesize(Data::Locate('Driver', $Call['Namespace'].'/'.$Driver.'.php'));

        asort($Sizes);
        list ($Min) = $Sizes;
        
        return $Min;
    });
