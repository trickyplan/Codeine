<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        //if (preg_match_all('@<place>(.*)<\/place>@SsUu', $Call['Layout'], $Places))
        {
            if (isset($Call['Output']))
            {
                if (is_array($Call['Output']))
                    foreach ($Call['Output'] as $Place => $Widgets)
                        foreach ($Widgets as $Key => $Widget)
                            if (isset($Widget['Type']))
                            $Call['Output'][$Place][$Key] =
                                F::Run($Call['View']['Renderer']['Service'] . '.Element.' . $Widget['Type'], 'Make', $Widget);

                // TODO Normal caching
            }
            else
                $Call['Output']['Content'] = array ('No output'); // FIXME Add Hook
        }

        return $Call;
    });