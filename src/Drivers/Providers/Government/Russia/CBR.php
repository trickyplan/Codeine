<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Code2Name', function ($Call)
    {
        $InternalCode = F::Run('Code.Run.SOAP', 'Run',
          [
              'Run' =>
              [
                  'Service' => $Call['CBR']['WSDL']['Credit'],
                  'Method' => 'BicToIntCode',
                  'Call' => ['BicCode' => $Call['Value']]
              ]
          ])['BicToIntCodeResult'];

        $Info = simplexml_load_string(F::Run('Code.Run.SOAP', 'Run',
         [
             'Run' =>
             [
                 'RTTL'   => 86400,
                 'Service' => $Call['CBR']['WSDL']['Credit'],
                 'Method' => 'CreditInfoByIntCodeXML',
                 'Call' => ['InternalCode' => $InternalCode]
             ]
         ])['CreditInfoByIntCodeXMLResult']['any']);

        return $Info->CO->OrgFullName;
    });

    setFn('GetRates', function ($Call)
    {
        $Rates = [];

        $Result = simplexml_load_string(F::Run('IO', 'Read',
            [
                'Storage'   => 'Web',
                'Where'     => ['ID' => $Call['CBR']['XML']['Rates']],
                'IO One'    => true
            ]));

        foreach ($Result as $Currency)
            $Rates[(string) $Currency->CharCode] =
                (float) strtr($Currency->Value, ',', '.') / (int)$Currency->Nominal;

        return $Rates[$Call['Currency']];
    });