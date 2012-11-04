<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 7.x
     */

    setFn ('Parse', function ($Call)
    {
        if (preg_match_all('@<layout>(.*)</layout>@SsUu', $Call['Value'], $Call['Parsed']))
        {
            foreach ($Call['Parsed'][1] as $Ix => $Match)
            {
                list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $Match));

                $Call['Value'] = str_replace ($Call['Parsed'][0][$Ix],
                    F::Run ('View', 'Load', array('Scope' => $Asset, 'ID' => $ID)), $Call['Value']);
            }
        }

        return $Call['Value'];
    });