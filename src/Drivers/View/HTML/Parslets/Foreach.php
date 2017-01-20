<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Foreach = simplexml_load_string ($Call['Parsed']['Match'][$IX]);

            $Output = '';

            foreach($Foreach->key as $Key)
                $Output.= str_replace('<key/>', $Key, $Foreach->data->asXML());


            $Call['Output'] = str_replace ($Call['Parsed']['Match'][$IX], $Output, $Call['Output']);
        }

        return $Call;
    });