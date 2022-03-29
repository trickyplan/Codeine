<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Match', function ($Call) {
        return F::Run('Text.Regex.' . $Call['Regex Engine'], null, $Call);
    });

    setFn('All', function ($Call) {
        $Pattern = $Call['Pattern'];

        F::Start($Pattern);

        $Result = F::Run('Text.Regex.' . $Call['Regex Engine'], null, $Call);

        if ($Result === false) {
            F::Counter('Text.Regex.Unused');
        }

        F::Stop($Pattern);

        return $Result;
    });