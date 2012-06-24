<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $Ix => $Match)
        {
            list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $Match));
            $Call['Output'] = str_replace ($Call['Parsed'][0][$Ix],
                F::Run ('View', 'Load', array('Scope' => $Asset, 'ID' => $ID)),$Call['Output']);
        }

        return $Call;
    });