<?php

    /* Codeine
     * @author BreathLess
     * @description: JSON Renderer
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Process', function ($Call)
    {
        if (is_array($Call['Value']['Items']))
            foreach ($Call['Value']['Items'] as $ID => $Item)
            {
                $Output[$ID] = Code::Run(
                    array('_N'=>'View.UI.JSON.'.$Item['UI'],
                         '_F' => 'Make',
                         'D' => $Item ['UI'],
                         'Item'=> Core::Any($Item))
                );
            }
        else
            $Output = $Call['Value'];

        return json_encode($Output, JSON_NUMERIC_CHECK && JSON_PRETTY_PRINT);
    });
