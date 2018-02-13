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
        $Call = F::Dot($Call, 'RTB.DSP.Request.site.page', F::Dot($Call, 'HTTP.URL'));
        $Call = F::Dot($Call, 'RTB.DSP.Request.site.ref', F::Dot($Call, 'Request.Referrer'));

        /*$Domain = F::Dot($Call, 'AdRam.Site.URL');
        $Domain = parse_url($Domain, PHP_URL_HOST);*/

        if (!empty($Domain))
            $Call = F::Dot($Call, 'RTB.DSP.Request.site.domain', $Domain);

        return $Call;
    });