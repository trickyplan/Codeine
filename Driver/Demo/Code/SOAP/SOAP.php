<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.03.11
     * @time 2:06
     */

    self::Fn('Do', function ($Call)
    {
        var_dump(Code::Run(
            array(
                'N' => 'http://erc.local/api/soap?wsdl',
                'F' => 'make',
                'Service' => 'Configurator',
                'Method' => 'read',
                'Component'=>'Log',
                'Type'=>'array',
                'Key' => '/loggers/log1'), Code::Ring2, null, 'SOAP'));
        return ;
    });
