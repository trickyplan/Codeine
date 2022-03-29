<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.6.2
     */

    F::_loadSource('Entity.Control');

    setFn('Test', function ($Call) {
        return F::Run('IO.Log', 'Autotest', $Call);
    });