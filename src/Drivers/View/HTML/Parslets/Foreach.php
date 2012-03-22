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
            $Foreach = simplexml_load_string ('<?xml version=\'1.0\'?><foreach>'.$Match.'</foreach>');

            $Output = '';

            foreach($Foreach->key as $Key)
                $Output.= str_replace('<key/>', $Key, $Foreach->data->asXML());


            $Call['Output'] = str_replace ($Call['Parsed'][0][$Ix], $Output, $Call['Output']);
        }

        return $Call['Output'];
    });