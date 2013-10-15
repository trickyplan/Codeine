<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (mb_strlen($Call['Value']) > $Call['Chars'])
        {
            $Cutted = mb_substr($Call['Value'], 0, $Call['Chars']);

            $Call['Hellip'] =
                isset($Call['More'])?
                    '<a href="'.$Call['More'].'">'.$Call['Hellip'].'</a>': $Call['Hellip'];

            $Cutted .= $Call['Hellip'];
        }
        else
            $Cutted = $Call['Value'];

        return $Cutted;
    });