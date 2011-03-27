<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.03.11
     * @time 2:00
     */

    self::Fn('Run', function ($Call)
    {
        $Call['Call'] = array_merge_recursive($Call['Call'],
                    array('Service' => $Call['Call']['N'],
                        'Method' => $Call['Call']['F']));
        
        $client = new SoapClient('http://erc.local/api/soap?wsdl');
        return $client->make($Call['Call']);
    });
