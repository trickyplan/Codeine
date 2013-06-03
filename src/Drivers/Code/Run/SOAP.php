<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $SOAP = new SoapClient($Call['Service']);

        F::Log($Call['Service'].'->'.$Call['Method']);

        return $SOAP->__soapCall($Call['Method'], [$Call['Call']]);
     });