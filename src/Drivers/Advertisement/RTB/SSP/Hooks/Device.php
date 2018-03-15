<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Call = F::Dot($Call, 'RTB.DSP.Request.device.ip', F::Dot($Call, 'HTTP.IP'));
        $Call = F::Dot($Call, 'RTB.DSP.Request.device.ua', F::Dot($Call, 'HTTP.Agent'));
        $Call = F::Dot($Call, 'RTB.DSP.Request.device.language', F::Dot($Call, 'Locale'));
        $Call = F::Dot($Call, 'RTB.DSP.Request.device.dpidsha1', sha1(F::Dot($Call, 'HTTP.Cookie.SSID')));
        return $Call;
    });