<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        /*$Call = F::Dot($Call, 'RTB.DSP.Request.site', F::Dot($Call, 'RTB.SSP.Site'));*/
        //$Call = F::Dot($Call, 'RTB.DSP.Request.site.name', F::Dot($Call, 'AdRam.Site.Title'));

        $page = F::Dot($Call, 'HTTP.URL');
        $ref = F::Dot($Call, 'Request.Referrer');
        $Call = F::Dot($Call, 'RTB.DSP.Items', array_map(function ($DSP) use ($page, $ref) {
            $DSP['Request']['site']['page'] = $page;

            if ($ref)
                $DSP['Request']['site']['ref'] = $ref;

            return $DSP; 
        }, F::Dot($Call, 'RTB.DSP.Items')));

        /*$Domain = F::Dot($Call, 'AdRam.Site.URL');
        $Domain = parse_url($Domain, PHP_URL_HOST);*/

        if (!empty($Domain))
            $Call = F::Dot($Call, 'RTB.DSP.Request.site.domain', $Domain);

        return $Call;
    });