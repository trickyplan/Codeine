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

        $Policies = F::Dot($Call, 'System.Interface.HTTP.Headers.Permissions-Policy');
        foreach ($Policies as $Policy => $Decision)
            $Header[] = $Policy."=".F::Variable($Decision, $Call);

        $Call['HTTP']['Headers']['Permissions-Policy:'] = implode(', ', $Header);

        return $Call;
    });