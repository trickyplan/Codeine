<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2019.x
     */

    setFn('Do', function ($Call) {
        $Headers = [];

        $Policies = F::Dot($Call, 'System.Interface.HTTP.Headers.Content-Security-Policy');

        if (empty($Policies))
        {
            F::Log ('No Content-Security-Policy is set. Consider adding one.', LOG_INFO, 'Security');
        }
        else
        {
            foreach ($Policies as $Policy => $Directives) {
                $Headers[] = $Policy . ' ' . implode(' ', $Directives);
            }

            $Call['HTTP']['Headers']['Content-Security-Policy:'] = implode(';', $Headers);
        }

        return $Call;
    });
