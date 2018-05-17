<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeRTBRequest', function ($Call)
    {
        $URL = F::Dot($Call, 'RTB.URL');
        
        $Call = F::Dot($Call, 'RTB.Request.site.id', F::Dot($Call, 'RTB.Site.ID'));
        $Call = F::Dot($Call, 'RTB.Request.site.name', F::Dot($Call, 'RTB.Site.Title'));
        $Call = F::Dot($Call, 'RTB.Request.site.page', F::Dot($Call, 'HTTP.URL'));
        $Call = F::Dot($Call, 'RTB.Request.site.ref', F::Dot($Call, 'Request.Referrer'));

        if (null === F::Dot($Call, 'RTB.Site.ID'))
            ;
        else
            $Call = F::Dot($Call, 'RTB.Request.site.domain', F::Dot($Call, 'RTB.Site.ID'));
            
        $Domain = parse_url($URL, PHP_URL_HOST);
        
        if (empty($Domain))
            ;
        else
            $Call = F::Dot($Call, 'RTB.Request.site.domain', $Domain);

        return $Call;
    });