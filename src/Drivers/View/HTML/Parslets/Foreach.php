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
            $Foreach = simplexml_load_string ($Call['Parsed']['Match'][$IX]);

            $Output = '';

            foreach($Foreach->key as $Key)
                $Output.= str_replace('<key/>', $Key, $Foreach->data->asXML());

            $Replaces[$IX] = $Output;
        }

        return $Replaces;
    });