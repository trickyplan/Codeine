<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        $Decision = true;

        if ($Rules = F::Dot($Call, 'HTTP.Filter.Referrer.Rules') and isset($Call['HTTP']['Referrer'])) {
            foreach ($Rules as $FilterName => $Filter) {
                foreach ($Filter as $Match) {
                    if (preg_match('/' . $Match . '/Ssu', $Call['HTTP']['Referrer'])) {
                        F::Log('HTTP Referrer Filter *' . $FilterName . '* matched', LOG_NOTICE, 'Security');
                        $Decision = false;
                    }
                }
            }
        }

        return $Decision;
    });