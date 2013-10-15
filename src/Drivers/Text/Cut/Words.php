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
            $Cutted = mb_substr($Call['Value'], 0, mb_strpos($Call['Value'], $Words[$Call['Words']])-1);

            $Call['Hellip'] =
                isset($Call['More'])?
                    '<a href="'.$Call['More'].'">'.$Call['Hellip'].'</a>': $Call['Hellip'];

            $Cutted .= $Call['Hellip'];
        }
        else
            $Cutted = $Call['Value'];

        return $Cutted;
    });