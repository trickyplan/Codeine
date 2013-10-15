<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Sentences = preg_split('/[.!?;]+/', $Call['Value']);

        if (count($Sentences) > $Call['Sentences'])
        {
            $Cutted = mb_substr($Call['Value'], 0, mb_strpos($Call['Value'], $Sentences[$Call['Sentences']])-1);

            $Call['Hellip'] =
                isset($Call['More'])?
                    '<a href="'.$Call['More'].'">'.$Call['Hellip'].'</a>': $Call['Hellip'];

            $Cutted .= $Call['Hellip'];
        }
        else
            $Cutted = $Call['Value'];

        return $Cutted;
    });