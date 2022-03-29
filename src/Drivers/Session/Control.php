<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    F::_loadSource('Entity.Control');

    setFn('Cleanup', function ($Call) {
        return F::Run('Session.Cleanup', 'Do', $Call);
    });