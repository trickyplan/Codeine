<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $Ix => $Match)
        {
            list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Match]);
            $Call['Output'] = str_replace ($Call['Parsed'][0][$Ix],
                F::Run ('View', 'Load', ['Scope' => $Asset, 'ID' => $ID]),$Call['Output']);
        }

        return $Call;
    });