<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Match', function ($Call) {
        $Pockets = null;

        if (empty($Call['Value'])) {
            F::Log('Empty Value', LOG_DEBUG);
        } else {
            if (preg_match('@' . $Call['Pattern'] . '@SsUu', $Call['Value'], $Pockets))
                ;
            else
                $Pockets = null;
        }

        return $Pockets;
    });

    setFn('All', function ($Call) {
        $Pockets = null;

        if (empty($Call['Value'])) {
            F::Log('Empty Value', LOG_DEBUG);
        } else {
            if (preg_match_all('@' . $Call['Pattern'] . '@Ssu', $Call['Value'], $Pockets))
                ;
            else
                $Pockets = null;
        }

        return $Pockets;
    });

