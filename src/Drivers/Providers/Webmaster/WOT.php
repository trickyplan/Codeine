<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 45.x
     */

    setFn('Auth', function ($Call) {
        if ($Call['ID'] == F::Dot($Call, 'Webmaster.WOT.ID')) {
            $Call['Output']['Content'][] = F::Dot($Call, 'Webmaster.WOT.Hash');
        } else {
            $Call = F::Apply('Error.Page', 'Do', $Call, ['Code' => 404]);
        }

        return $Call;
    });
