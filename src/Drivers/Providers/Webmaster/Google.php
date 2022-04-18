<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 45.x
     */

    setFn('Auth', function ($Call) {
        $ID = F::Dot($Call, 'Webmaster.Google.ID');
        $Call = F::Dot($Call, 'Webmaster.Google.ID', F::Variable($ID, $Call));

        if ($Call['ID'] == F::Dot($Call, 'Webmaster.Google.ID')) {
            $Call['Output']['Content'][] =
                F::Dot($Call, 'Webmaster.Google.Prefix')
                . F::Dot($Call, 'Webmaster.Google.ID')
                . F::Dot($Call, 'Webmaster.Google.Postfix');
        } else {
            $Call = F::Apply('Error.Page', 'Do', $Call, ['Code' => 404]);
        }

        return $Call;
    });
