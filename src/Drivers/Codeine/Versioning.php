<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        F::loadOptions('Version');

        if (isset(F::$_Options['Project']['Version']['Codeine'])
            && F::$_Options['Project']['Version']['Codeine'] > F::$_Options['Version']['Codeine']) {
            trigger_error(
                'Codeine ' . F::$_Options['Project']['Version']['Codeine'] . '+ needed. Installed: '
                . F::$_Options['Version']['Codeine']
            );
        }

        if (isset(F::$_Options['Version']['Codeine'])) {
            F::Log('Codeine: *' . F::$_Options['Version']['Codeine'] . '*', LOG_INFO);
        }

        if (isset(F::$_Options['Version']['Project'])) {
            F::Log('Project: *' . F::$_Options['Version']['Project'] . '*', LOG_INFO);
        }

        return $Call;
    });