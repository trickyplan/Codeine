<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['CSS']['Styles']))
        {
            $Call['CSS']['Styles'] = [
                'Combined' => implode('', $Call['CSS']['Styles'])
            ];
        }

        return $Call;
    });