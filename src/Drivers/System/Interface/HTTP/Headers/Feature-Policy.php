<?php

/* Codeine
 * @author bergstein@trickyplan.com
 * @description  
 * @package Codeine
 * @version 2019.x
 */

    setFn('Do', function ($Call)
    {
        $Header = [];

        $Policies = F::Dot($Call, 'System.Interface.HTTP.Headers.Feature-Policy');
        foreach ($Policies as $Policy => $Decision)
            $Header[] = $Policy." '".$Decision."'";

        $Call['HTTP']['Headers']['Feature-Policy:'] = implode('; ', $Header);

        return $Call;
    });