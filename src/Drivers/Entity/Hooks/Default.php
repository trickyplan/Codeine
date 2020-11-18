<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Default value support 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (($Default = F::Dot($Node, 'Default')) === null)
                ; // No Default
            else
            {
                if (F::Dot($Call['Data'], $Name) == null)
                {
                    if (F::isCall($Default))
                    {
                        $LiveDefault = F::Live($Default, $Call);
                        $Default = $LiveDefault;
                        F::Log( (function() use($LiveDefault) {return 'Live Default is processed: *' . $Name . '* = *' .j($LiveDefault). '*';}), LOG_DEBUG);
                    }

                    if (F::Dot($Node, 'Empty as Default'))
                        $Call['Data'] = F::Dot($Call['Data'], $Name, null);
                    else
                        $Call['Data'] = F::Dot($Call['Data'], $Name, $Default);
                }
            }
        }
        return $Call;
    });