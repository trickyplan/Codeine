<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Date() engine
     * @package Codeine
     * @version 8.x
     */

    setFn('Format', function ($Call) {
        try {
            if (isset($Call['Value']) && !empty($Call['Value'])) {
                $DT = new DateTime($Call['Value']);
            } else {
                $DT = new DateTime('now');
            }

            return $DT->format($Call['Format']);
        } catch (Exception $E) {
            return 'ERR';
        }
    });