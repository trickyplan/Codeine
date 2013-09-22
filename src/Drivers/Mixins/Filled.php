<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     */

    setFn('Get', function ($Call)
    {
        $Total = 0; $Filled = 0;

        $Data = $Call['Data'];

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Widgets']))
            {
                $Total++;
                $Value = F::Dot($Data, $Name);

                if (!empty($Value))
                    $Filled++;
            }
        }
        return round(($Filled/$Total)*100);
    });