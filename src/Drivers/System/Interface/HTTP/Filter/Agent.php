<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        $Decision = true;

        if ($Rules = F::Dot($Call, 'HTTP.Filter.Agent.Rules') and isset($Call['HTTP']['Agent'])) {
            foreach ($Rules as $FilterName => $Filter) {
                foreach ($Filter as $Match) {
                    if (preg_match('/' . $Match . '/Ssu', $Call['HTTP']['Agent'])) {
                        F::Log('HTTP Agent Filter *' . $FilterName . '* matched', LOG_NOTICE, 'Security');
                        $Decision = false;
                    }
                }
            }
        }

        return $Decision;
    });