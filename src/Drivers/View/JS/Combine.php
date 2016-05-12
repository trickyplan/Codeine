<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['JS']['Scripts']))
        {
            $Call['JS']['Scripts'] = [
                'Combined' => implode(';'.PHP_EOL, $Call['JS']['Scripts'])
            ];
        }

        return $Call;
    });