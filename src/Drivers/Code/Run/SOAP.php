<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $Hash = sha1(j($Call['Call']));
        $Scope = $Call['Service'].'/'.$Call['Method'];

        $Cached = F::Run('IO', 'Execute', ['Storage' => 'SOAP Cache', 'Scope' => $Scope, 'Execute' => 'Version', 'Where' => ['ID' => $Hash]]);

        if (isset($Call['Cache']))
        {
            if ($Cached > time() - $Call['Cache'])
            {
                $Result = F::Run('IO', 'Read', ['Storage' => 'SOAP Cache', 'Scope' => $Scope, 'Where' => ['ID' => $Hash]])[0];
                F::Log('Cached: '.$Call['Service'].'->'.$Call['Method'], LOG_INFO, 'Administrator');
            }
        }

        if (!isset($Result))
        {
            try
            {
                $SOAP = new SoapClient($Call['Service']);
                F::Log($Call['Service'].'->'.$Call['Method'], LOG_INFO, 'Administrator');

                $Result = $SOAP->__soapCall($Call['Method'], [$Call['Call']]);
            }
            catch (SoapFault $e)
            {
                F::Log($Call['Service'].'->'.$Call['Method'], LOG_ERR, 'Administrator');
                F::Log($e->getMessage(), LOG_ERR, 'Administrator');
                return $Cached;
            }

            $Result = jd(j($Result), true);
            F::Run('IO', 'Write', ['Storage' => 'SOAP Cache', 'Scope' => $Scope, 'Where' => ['ID' => $Hash], 'Data' => $Result]);
        }

        return $Result;
     });