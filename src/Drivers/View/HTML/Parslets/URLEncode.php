<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet
     * @package Codeine
     * @version 6.0
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            $Call['Replace'][$Call['Parsed']['Match'][$IX]] = rawurlencode($Match);
        }

        return $Call;
    });