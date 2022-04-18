<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 45.x
     */

    setFn('Auth', function ($Call) {
        $ID = F::Dot($Call, 'Webmaster.Yandex.ID');
        $Call = F::Dot($Call, 'Webmaster.Yandex.ID', F::Variable($ID, $Call));

        if ($Call['ID'] == F::Dot($Call, 'Webmaster.Yandex.ID')) {
            $Call['Output']['Content'][] = F::Variable(F::Dot($Call, 'Webmaster.Yandex.Template'), $Call);
        } else {
            $Call = F::Apply('Error.Page', 'Do', $Call, ['Code' => 404]);
        }

        return $Call;
    });
