<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call) {
        F::Log(
            '[DEPRECATED] Entity.List will be ousted. Use "Entity.List.Static" instead',
            LOG_WARNING,
            ['Developer', 'Deprecated']
        );
        return F::Apply('Entity.List.Static', null, $Call);
    });

    setFn('RAW', function ($Call) {
        F::Log(
            '[DEPRECATED] Entity.List will be ousted. Use "Entity.List.Static" instead',
            LOG_WARNING,
            ['Developer', 'Deprecated']
        );
        return F::Apply('Entity.List.Static', null, $Call);
    });

    setFn('RAW2', function ($Call) {
        F::Log(
            '[DEPRECATED] Entity.List will be ousted. Use "Entity.List.Static" instead',
            LOG_WARNING,
            ['Developer', 'Deprecated']
        );
        return F::Apply('Entity.List.Static', null, $Call);
    });