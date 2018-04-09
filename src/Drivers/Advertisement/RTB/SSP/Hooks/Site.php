<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $URL = F::Dot($Call, 'RTB.URL');
        $Referrer = F::Dot($Call, 'Request.Referrer');
        $Domain = parse_url($URL, PHP_URL_HOST);

        $Call = F::Dot($Call, 'RTB.DSP.Items', array_map(function ($DSP) use ($URL, $Referrer, $Domain)
        {
            if (empty($Domain))
                ;
            else
                $DSP['Request']['site']['domain'] = $Domain;
            
            $DSP['Request']['site']['page'] = $URL;

            if ($Referrer)
                $DSP['Request']['site']['ref'] = $Referrer;

            return $DSP; 
        }, F::Dot($Call, 'RTB.DSP.Items')));

        return $Call;
    });