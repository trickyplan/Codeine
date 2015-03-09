<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (
               (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']))
                or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                or (isset($_SERVER['HTTP_X_HTTPS']) &&$_SERVER['HTTP_X_HTTPS'])
            )
            $Call['HTTP']['Proto'] = 'https://';
        else
            $Call['HTTP']['Proto'] = 'http://';

        if (isset($Call['HTTP']['Force SSL']) && $Call['HTTP']['Force SSL'])
        {
            if ($Call['HTTP']['Proto'] !== 'https://')
                $Call = F::Run('System.Interface.HTTP', 'Remote Redirect', $Call,
                    ['Location' => 'https://'.$Call['HTTP']['Host'].$Call['HTTP']['URI']]);

            if (isset($Call['HTTP']['HSTS']['Enabled']) && $Call['HTTP']['HSTS']['Enabled'])
            {
                $Header = 'max-age='.$Call['HTTP']['HSTS']['Expire'];

                if (isset($Call['HTTP']['HSTS']['Subdomains']) && $Call['HTTP']['HSTS']['Subdomains'])
                    $Header.= '; includeSubdomains';

                $Call['HTTP']['Headers']['Strict-Transport-Security:'] = $Header;
            }
        }
        if (empty($Call['HTTP']['Proto']))
            F::Log('Protocol is *empty*!', LOG_INFO);
        else
            F::Log('Protocol is *'.$Call['HTTP']['Proto'].'*', LOG_INFO);

        return $Call;
    });