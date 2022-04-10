<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Skype Parslet
     * @package Codeine
     * @version 6.0
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '<strong>' . $Call['View']['Highlight'] . '</strong>';
        }

        return $Call;
    });
