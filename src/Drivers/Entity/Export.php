<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', $Call);

        foreach ($Elements as $Element)
            $Call['Output']['Content'][] = $Element;

        return $Call;
    });