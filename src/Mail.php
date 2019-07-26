<?php

    /**
     * @author bergstein@trickyplan.com
     * @time 5:17
     */

    require 'Core.php';

    if (file_exists('/etc/default/codeine'))
        define('Root', file_get_contents('/etc/default/codeine'));

    !defined('Root')? define('Root', getcwd()): false;

    echo "OKMAIL";
    //F::Log('Root folder: '.Root, LOG_INFO);
    include Root.'/vendor/autoload.php';

    try
    {
        $Call = F::Bootstrap
            ([
                'Paths' => [Root],
                'Environment' => isset($Opts['Environment'])? $Opts['Environment']: null,
                'Service' => 'System.Interface.Mail',
                'Method' => 'Do',
                'Call' => $Opts
            ]);

        F::Shutdown($Call);
    }
    catch (Exception $e)
    {
       F::Log($e->getMessage(), LOG_CRIT, 'Developer');
    }