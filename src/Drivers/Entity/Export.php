<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        ini_set('memory_limit', '800M'); // FIXME
        set_time_limit(0); // FIXME
        $Elements = F::Run('Entity', 'Read', $Call);

        foreach ($Elements as $Element)
            $Call['Output'][] = $Element;

        return $Call;
    });