<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Words = preg_split('/[\s,]+/', $Call['Value']);

        if (count($Words) > $Call['Words'])
        {
            if (empty($Call['Value']))
                $Cutted = $Call['Value'];
            else
                $Cutted = mb_substr($Call['Value'], 0, mb_strpos($Call['Value'], $Words[$Call['Words']])-1);
        }
        else
            $Cutted = $Call['Value'];

        return $Cutted;
    });