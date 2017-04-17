<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Cut Parslet
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        $Replaces = [];
        
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
            $Replaces[$IX] = F::Run('Text.Beautifier', 'Do', $Call,['Value' => $Match]);

        return $Replaces;
    });