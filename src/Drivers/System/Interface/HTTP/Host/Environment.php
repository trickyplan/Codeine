<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        // Select Default Host
        if (isset($_SERVER['HTTP_HOST'])) {
            $Host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);

            if (isset($_SERVER['CODEINE_HOST'])) {
                if ($Host == $_SERVER['CODEINE_HOST']) {
                    $Call['HTTP']['Host'] = $_SERVER['CODEINE_HOST'];

                    if (str_contains($Call['HTTP']['Host'], ':') !== false) {
                        list($Domain,) = explode(':', $Call['HTTP']['Host']);
                    } else {
                        $Domain = $Call['HTTP']['Host'];
                    }

                    $Call['HTTP']['Domain'] = $Domain;

                    F::Log('Host is determined: *' . $_SERVER['CODEINE_HOST'] . '*', LOG_INFO);
                } else {
                    F::Log('Host is not determined: *' . $_SERVER['HTTP_HOST'] . '*', LOG_INFO);
                    F::Shutdown(); // FIXME Add Options
                }
            } else {
                F::Log('CODEINE_HOST is not specified.', LOG_CRIT);
                F::Shutdown(); // FIXME Add Options
            }
        } else {
            F::Log('HTTP_HOST is not specified', LOG_CRIT);
        }

        return $Call;
    });
