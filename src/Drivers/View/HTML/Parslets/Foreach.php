<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Foreach = simplexml_load_string ($Call['Parsed'][0][$IX]);

            $Output = '';

            foreach($Foreach->key as $Key)
                $Output.= str_replace('<key/>', $Key, $Foreach->data->asXML());


            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Output, $Call['Output']);
        }

        return $Call['Output'];
    });