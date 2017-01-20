<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $Ix => $Match)
        {
            list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Match]);
            $Call['Output'] = str_replace ($Call['Parsed']['Match'][$Ix],
                F::Run ('View', 'Load', $Call, ['Scope' => $Asset, 'ID' => $ID]),$Call['Output']);
        }

        return $Call;
    });