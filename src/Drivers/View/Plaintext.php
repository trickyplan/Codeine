<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Render', function ($Call)
    {
        $Call['Headers']['Content-type:'] = 'text/plain';

        if (is_array($Call['Output']))
            foreach ($Call['Output']['Content'] as $Key => $Widget)
                if(is_array($Widget))
                    $Call['Output']['Content'][$Key] = F::Run($Call['Renderer'] . '.Element.' . $Widget['Type'], 'Make', $Widget);
                else
                    $Call['Output']['Content'][$Key] = $Widget;

        $Call['Output'] = implode("\n", $Call['Output']['Content']);

        return $Call;
    });
