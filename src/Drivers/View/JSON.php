<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Render', function ($Call)
    {
        $Call = F::Hook('beforeJSONRender', $Call);

        $Call = F::Run('View.Pipeline', 'Do', $Call);
        $Call['Output'] = j($Call['Output']['Content']);

        $Call = F::Hook('afterJSONRender', $Call);

        return $Call;
    });