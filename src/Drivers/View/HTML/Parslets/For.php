<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][1] as $Ix => $Match)
        {
            $For = simplexml_load_string('<?xml version=\'1.0\'?><for>'.$Match.'</for>');
            $Call['Output'] = str_replace ($Call['Parsed'][0][$Ix], str_repeat($For->data->asXML(), (integer) $For->count),$Call['Output']);
        }

        return $Call['Output'];
    });