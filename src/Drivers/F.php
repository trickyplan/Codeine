<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Log', function ($Call)
    {
        if (preg_match_all('@\$([\.\w]+)@', $Call['Message'], $Vars))
        {
            foreach ($Vars[0] as $IX => $Key)
                $Call['Message'] = str_replace($Key, F::Dot($Call,$Vars[1][$IX]) , $Call['Message']);
        }

        F::Log($Call['Message'], $Call['Type']);

        return $Call;
    });