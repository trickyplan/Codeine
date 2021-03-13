<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    self::_loadSource('Entity.Control');

    setFn('Cleanup', function ($Call)
    {
        return F::Run('Session.Cleanup', 'Do', $Call);
    });