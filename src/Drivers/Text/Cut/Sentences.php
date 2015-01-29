<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Sentences = preg_split('/[\.\!\?]+/Ssu', $Call['Value']);

        if (count($Sentences) > $Call['Sentences'] && !empty($Sentences[$Call['Sentences']]))
            $Cutted = mb_substr($Call['Value'], 0, mb_strpos($Call['Value'], $Sentences[$Call['Sentences']])-1);
        else
            $Cutted = $Call['Value'];

        return $Cutted;
    });