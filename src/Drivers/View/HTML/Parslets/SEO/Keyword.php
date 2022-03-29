<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            $Link = $Call['Parsed']['Value'][$IX];
            $Call['SEO']['Keywords'][] = $Link;
            $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '';
        }

        return $Call;
    });