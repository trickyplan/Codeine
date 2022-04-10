<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        $Status = 'Passed';

        if (isset($Call['Test']['Case']['Assert'])) {
            foreach ($Call['Test']['Case']['Assert'] as $Assert => $Expected) {
                $Call = F::Dot($Call, 'Test.Case.Result.' . $Assert . '.Expected', $Expected);
                F::Start($Call['SCID'] . '.' . $Assert);

                $Decision = F::Run('Test.Hooks.Result.Assert.' . $Assert, 'Do', $Call);
                $Call = F::Dot($Call, 'Test.Case.Assert.' . $Assert . '.Decision', $Decision);

                if ($Decision == false) {
                    $Status = 'Failed';
                }

                F::Stop($Call['SCID'] . '.' . $Assert);

                $Call = F::Dot(
                    $Call,
                    'Test.Case.Time.' . $Assert,
                    F::Time($Call['SCID'] . '.' . $Assert)
                );
            }
        }

        $Call['Test']['Case']['Status'] = $Status;

        return $Call;
    });
