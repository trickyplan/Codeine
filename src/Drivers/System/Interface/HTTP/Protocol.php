<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        $Call = F::Apply(null, 'Detect.HTTPS', $Call);
        $Call = F::Apply(null, 'Force.HTTPS', $Call);
        $Call = F::Apply(null, 'Set.FQDN', $Call);


        if (empty($Call['HTTP']['Proto'])) {
            F::Log('Protocol is *empty*!', LOG_INFO);
        } else {
            F::Log('Protocol is *' . $Call['HTTP']['Proto'] . '*', LOG_INFO);
        }


        return $Call;
    });

    setFn('Detect.HTTPS', function ($Call) {
        $Secure = false;

        if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) {
            $Secure = true;
            F::Log('HTTPS detected by Server.HTTPS', LOG_INFO);
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $Secure = true;
            F::Log('HTTPS detected by Server.HTTP_X_FORWARDED_PROTO', LOG_INFO);
        }
        if (isset($_SERVER['HTTP_X_HTTPS']) && $_SERVER['HTTP_X_HTTPS']) {
            $Secure = true;
            F::Log('HTTPS detected by Server.HTTP_X_HTTPS', LOG_INFO);
        }

        if ($Secure) {
            $Call['HTTP']['Proto'] = 'https://';
        } else {
            $Call['HTTP']['Proto'] = 'http://';
        }

        return $Call;
    });

    setFn('Force.HTTPS', function ($Call) {
        if (isset($Call['HTTP']['Force SSL']) && $Call['HTTP']['Force SSL']) {
            if ($Call['HTTP']['Proto'] !== 'https://') {
                $Call = F::Run(
                    'System.Interface.HTTP',
                    'Remote Redirect',
                    $Call,
                    ['Redirect' => 'https://' . $Call['HTTP']['Host'] . $Call['HTTP']['URI']]
                );
            }

            if (isset($Call['HTTP']['HSTS']['Enabled']) && $Call['HTTP']['HSTS']['Enabled']) {
                $Header = 'max-age=' . $Call['HTTP']['HSTS']['Expire'];

                if (isset($Call['HTTP']['HSTS']['Subdomains']) && $Call['HTTP']['HSTS']['Subdomains']) {
                    $Header .= '; includeSubdomains';
                }

                if (isset($Call['HTTP']['HSTS']['Preload']) && $Call['HTTP']['HSTS']['Preload']) {
                    $Header .= '; preload';
                }

                $Call['HTTP']['Headers']['Strict-Transport-Security:'] = $Header;
            }
        }
        return $Call;
    });

    setFn('Set.FQDN', function ($Call) {
        $Call['HTTP']['FQDN'] = $Call['HTTP']['Proto'] . $Call['HTTP']['Host'];
        if ($Call['HTTP']['Proto'] === 'http' && $Call['HTTP']['Port'] != 80) {
            $Call['HTTP']['FQDN'] .= ':' . $Call['HTTP']['Port'];
        }

        if ($Call['HTTP']['Proto'] === 'https' && $Call['HTTP']['Port'] != 443) {
            $Call['HTTP']['FQDN'] .= ':' . $Call['HTTP']['Port'];
        }
        return $Call;
    });