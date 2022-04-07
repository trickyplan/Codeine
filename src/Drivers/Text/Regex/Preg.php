<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Match', function ($Call) {
        $Pockets = false;

        if (empty($Call['Value'])) {
            F::Log('Empty Value', LOG_DEBUG);
        } else {
            if (preg_match('@' . $Call['Pattern'] . '@SsUu', $Call['Value'], $Pockets))
                ;
            else
                $Pockets = false;
        }

        return $Pockets;
    });

    setFn('All', function ($Call) {
        $Pockets = false;

        if (empty($Call['Value'])) {
            F::Log('Empty Value', LOG_DEBUG);
        } else {
            if (preg_match_all('@' . $Call['Pattern'] . '@Ssu', $Call['Value'], $Pockets))
                ;
            else
                $Pockets = false;
        }

        return $Pockets;
    });

