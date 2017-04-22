<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        $Replaces = [];
        
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
            $Replaces[$IX] = F::Run ('Locale', 'Get', $Call, ['Message' => $Match]);

        return $Replaces;
    });