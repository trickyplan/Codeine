<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $Hash = sha1(json_encode($Call['Call']));
        $Scope = $Call['Service'].'/'.$Call['Method'];

        if (isset($Call['Cache']))
        {
            $Cached = F::Run('IO', 'Execute', ['Storage' => 'SOAP Cache', 'Scope' => $Scope, 'Execute' => 'Version', 'Where' => ['ID' => $Hash]]);

            if ($Cached > time() - $Call['Cache'])
            {
                $Result = F::Run('IO', 'Read', ['Storage' => 'SOAP Cache', 'Scope' => $Scope, 'Where' => ['ID' => $Hash]])[0];
                F::Log('Cached: '.$Call['Service'].'->'.$Call['Method'], LOG_INFO, 'Administrator');
            }
        }

        if (!isset($Result))
        {
            $SOAP = new SoapClient($Call['Service']);
            F::Log($Call['Service'].'->'.$Call['Method'], LOG_INFO, 'Administrator');

            try
            {
                $Result = $SOAP->__soapCall($Call['Method'], [$Call['Call']]);
            }
            catch (SoapFault $e)
            {
                return null;
            }

            $Result = json_decode(json_encode($Result), true);
            F::Run('IO', 'Write', ['Storage' => 'SOAP Cache', 'Scope' => $Scope, 'Where' => ['ID' => $Hash], 'Data' => $Result]);
        }

        return $Result;
     });