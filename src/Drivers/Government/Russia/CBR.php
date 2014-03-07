<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Code2Name', function ($Call)
    {
        $InternalCode = F::Run('Code.Run.SOAP', 'Run',
          [
              'Service' => $Call['CBR']['WSDL']['Credit'],
              'Method' => 'BicToIntCode',
              'Call' => ['BicCode' => '042202824']
          ])['BicToIntCodeResult'];

        $Info = simplexml_load_string(F::Run('Code.Run.SOAP', 'Run',
         [
             'Cache'   => 86400,
             'Service' => $Call['CBR']['WSDL']['Credit'],
             'Method' => 'CreditInfoByIntCodeXML',
             'Call' => ['InternalCode' => $InternalCode]
         ])['CreditInfoByIntCodeXMLResult']['any']);

        return $Info->CO->OrgFullName;
    });

    setFn('GetRates', function ($Call)
    {
        $Rates = [];

        $Result = simplexml_load_string(F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Where' => ['ID' => $Call['CBR']['XML']['Rates']]
            ])[0]);

        foreach ($Result as $Currency)
            $Rates[(string) $Currency->CharCode] =
                (float) strtr($Currency->Value, ',', '.') / (int)$Currency->Nominal;

        return $Rates[$Call['Currency']];
    });