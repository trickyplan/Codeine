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
        {
            list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Match]);
            $Replaces[$IX] = F::Run ('View', 'Load', $Call, ['Scope' => $Asset, 'ID' => $ID]);
        }

        return $Replaces;
    });