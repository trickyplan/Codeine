<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Match', function ($Call)
    {
        return F::Run('Text.Regex.'.$Call['Regex Engine'], null, $Call);
    });

    setFn('All', function ($Call)
    {
        $Pattern = $Call['Pattern'];

        self::Start($Pattern);

            $Call = F::Run('Text.Regex.'.$Call['Regex Engine'], null, $Call);

        self::Stop($Pattern);

        return $Call;
    });