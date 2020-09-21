<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        // Select Default Host
        if (isset($_SERVER['HTTP_HOST']))
        {
            if (isset($_SERVER['CODEINE_DOMAIN']))
            {
                if ($_SERVER['HTTP_HOST'] == $_SERVER['CODEINE_DOMAIN'])
                {
                    $Call['HTTP']['Host'] = $_SERVER['CODEINE_DOMAIN'];
                    if (mb_strpos($Call['HTTP']['Host'], ':') !== false)
                        list($Domain, $Port) = explode(':', $Call['HTTP']['Host']);
                    else
                        $Domain = $Call['HTTP']['Host'];

                    $Call['HTTP']['Domain'] = $Domain;

                    F::Log('Host is determined: *'.$_SERVER['CODEINE_DOMAIN'].'*', LOG_INFO);
                }
                else
                {
                    F::Log('Host is not determined: *'.$_SERVER['HTTP_HOST'].'*', LOG_INFO);
                    F::Shutdown(); // FIXME Add Options
                }
            }
            else
            {
                F::Log('CODEINE_DOMAIN is not specified.', LOG_CRIT);
                F::Shutdown(); // FIXME Add Options
            }
        }
        else
            F::Log('HTTP_HOST is not specified', LOG_CRIT);

        return $Call;
    });
