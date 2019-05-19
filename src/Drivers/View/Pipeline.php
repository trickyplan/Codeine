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
                $AllowedWidgets = F::Dot($Call, 'Renderer.Widgets.Allowed');
                if (is_array($Call['Output']))
                    foreach ($Call['Output'] as $Place => $Widgets)
                        if (is_array($Widgets))
                        {
                            foreach ($Widgets as $Key => $Widget)
                                if (isset($Widget['Type']))
                                {
                                    if ($AllowedWidgets === null or in_array($Widget['Type'], $AllowedWidgets))
                                        $Widget = F::Run($Call['View']['Renderer']['Service']
                                            .'.Element.'
                                            .$Widget['Type'], 'Make', $Widget, [
                                                'View'      => $Call['View'],
                                                'Locale'    => $Call['Locale']
                                        ]);

                                    $Call['Output'][$Place][$Key] = $Widget;
                                }
                        }
            }
        }


        return $Call;
    });