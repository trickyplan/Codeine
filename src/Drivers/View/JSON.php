<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Render', function ($Call)
    {
        $Call['Output'] = json_encode($Call['Output']['Content'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $Call = F::Hook('beforeJSONRender', $Call);

        $Call['Output'] = str_replace(["\n","\r"],"",$Call['Output']);

        $Call['Output'] = json_encode(
                    json_decode($Call['Output'], true),
                    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $Call = F::Hook('afterJSONRender', $Call);

        return $Call;
    });