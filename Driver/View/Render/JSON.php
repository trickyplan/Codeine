<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: JSON Renderer
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 16.11.10
     * @time 3:38
     */

    self::Fn('Do', function ($Call)
    {
        if (is_array($Call['Input']['Items']))
            foreach ($Call['Input']['Items'] as $ID => $Item)
            {
                $Output[$ID] = Code::Run(
                    array('N'=>'View.UI.JSON.'.$Item['UI'],
                         'F' => 'Make',
                         'D' => $Item ['UI'],
                         'Item'=> Core::Any($Item))
                );
            }
        else
            $Output = $Call['Input'];

        return json_encode($Output);
    });
