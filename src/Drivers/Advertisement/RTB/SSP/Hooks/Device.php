<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeRTBRequest', function ($Call)
    {
        $dpidsha1 = sha1(F::Dot($Call, 'HTTP.Cookie.SSID'));
        $ua = F::Dot($Call, 'HTTP.Agent');
        $ip = F::Dot($Call, 'HTTP.IP');

        $Call = F::Dot($Call, 'RTB.DSP.Items', array_map(function ($DSP) use ($ip, $ua, $dpidsha1) {
            $DSP['Request']['device']['ip'] = $ip;
            $DSP['Request']['device']['ua'] = $ua;
            $DSP['Request']['device']['dpidsha1'] = $dpidsha1;
            return $DSP;
        }, F::Dot($Call, 'RTB.DSP.Items')));
        return $Call;
    });