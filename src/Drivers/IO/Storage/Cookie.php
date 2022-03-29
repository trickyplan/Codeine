<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 43.6.0
     */
    setFn('Open', function ($Call) {
        return $Call;
    });

    setFn('Read', function ($Call) {
        if (PHP_SAPI == 'cli') {
            return null;
        }

        return isset($Call['HTTP']['Cookie'][$Call['Where']['ID']]) ? $Call['HTTP']['Cookie'][$Call['Where']['ID']] : null;
    });

    setFn('Write', function ($Call) {
        if (null !== $Call['Data']) {
            if ($Return = setcookie(
                $Call['Where']['ID'],
                $Call['Data'],
                [
                    'expires' => $Call['Cookie']['TTL'] + time(),
                    'path' => $Call['Cookie']['Path'],
                    'domain' => $Call['Cookie']['Domain'] ?? $Call['HTTP']['FQDN'],
                    'secure' => $Call['Cookie']['Secure'],
                    'httponly' => $Call['Cookie']['HTTP Only'],
                    'samesite' => $Call['Cookie']['Same Site']
                ]
            )) {
                $Call['HTTP']['Cookie'][$Call['Where']['ID']] = $Call['Data'];
            } else {
                $Call = F::Hook('Cookie.Set.Failed', $Call);
            }
        } else {
            $Return = setcookie($Call['HTTP']['Cookie'][$Call['Where']['ID']], '');
        }

        return $Return;
    });