<?php

/* Codeine
 * @author bergstein@trickyplan.com
 * @description  
 * @package Codeine
 * @version 2019.x
 */

    setFn('Lookup', function ($Call)
    {
        exec($Call['Whois']['Command'].' '.$Call['Whois']['Domain'], $Result, $Status);
        $WhoisParsed = [];
        if ($Status == 0)
        {
            foreach ($Result as &$Line)
                if (preg_match('/^([^:]*)\:(.*)$/Ssu', trim($Line), $Pockets))
                    if (in_array($Pockets[1], $Call['Whois']['Keys']))
                        $WhoisParsed[trim($Pockets[1])] = trim($Pockets[2]);
        }
        else
        {
            F::Log('Whois return code is: '.$Status, LOG_NOTICE);
            F::Log('Whois output: '.implode('', $Result), LOG_INFO);
        }

        return $WhoisParsed;
    });

    setFn('Get.RegistryExpireDate', function ($Call)
    {
        if ($Date = F::Dot($Call, 'Whois.Data.Registry Expiry Date'))
        {
            $DT = date_create_from_format ( 'Y-m-d\TH:i:s\Z', $Date);
            if ($DT)
                $Result = $DT->getTimestamp();
            else
                $Result = null;
        }
        else
            $Result = null;

        return $Result;
    });