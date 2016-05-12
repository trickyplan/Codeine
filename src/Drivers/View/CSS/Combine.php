<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
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