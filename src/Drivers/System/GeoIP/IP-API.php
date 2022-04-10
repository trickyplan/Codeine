<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Determine', function ($Call) {
        $Unified = [];

        $Response = F::Run(
            'IO',
            'Read',
            [
                'Storage' => 'Web',
                'Format' => 'Format.JSON',
                'Get First' => true,
                'Where' => F::Dot($Call, 'System.GeoIP.IP-API.Prefix')
                    . $Call['HTTP']['IP'] . F::Dot($Call, 'System.GeoIP.IP-API.Postfix')
            ]
        );

        if (empty($Response)) {
            F::Log('Empty Response for ' . $Call['HTTP']['IP'], LOG_WARNING);
        } else {
            $Map = F::Dot($Call, 'System.GeoIP.IP-API.Map');

            foreach ($Map as $ForeignKey => $NativeKey) {
                if (isset($Response[$ForeignKey])) {
                    $Unified = F::Dot($Unified, $NativeKey, $Response[$ForeignKey]);
                }
            }
        }

        return $Unified;
    });
