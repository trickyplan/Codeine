<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        //if (preg_match_all('@<place>(.*)<\/place>@SsUu', $Call['Layout'], $Places))
        {
            if (isset($Call['Output']))
            {
                $Output = [];

                if (is_array($Call['Output']))
                    foreach ($Call['Output'] as $Place => $Widgets)
                        if (is_array($Widgets))
                        {
                            foreach ($Widgets as $Key => $Widget)
                                if (isset($Widget['Type']))
                                {
                                    $Widget =
                                        F::Run($Call['View']['Renderer']['Service'] . '.Element.' . $Widget['Type'], 'Make', $Widget);

                                    $Call['Output'][$Place][$Key] = $Widget;
                                }
                        }
                // TODO Normal caching
            }
        }


        return $Call;
    });