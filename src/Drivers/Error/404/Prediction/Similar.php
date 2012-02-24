<?php

/* Codeine
     * @author BreathLess
     * @description Similar text & Static Links 
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Suggest', function ($Call)
    {
        $Keys = array_keys($Call['Request']);
        $Static = F::loadOptions('Code.Routing.Static');
        $Levels = array ();

        foreach ($Static['Links'] as $URL => $Link)
            $Levels[$URL] = similar_text($URL, $Keys[0]);

        asort($Levels);

        return array_pop(array_keys($Levels));
    });