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
             'Cache'   => 86400,
             'Service' => 'http://www.cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx?WSDL',
             'Method' => 'BicToIntCode',
             'Call' => ['BicCode' => $Call['Value']]
         ])['BicToIntCodeResult'];

        $Info = simplexml_load_string(F::Run('Code.Run.SOAP', 'Run',
         [
             'Cache'   => 86400,
             'Service' => 'http://www.cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx?WSDL',
             'Method' => 'CreditInfoByIntCodeXML',
             'Call' => ['InternalCode' => $InternalCode]
         ])['CreditInfoByIntCodeXMLResult']['any']);

        return $Info->CO->OrgFullName;
    });

    setFn('GetRates', function ($Call)
    {
        $Rates = [];
        $LatestDate = F::Run('Code.Run.SOAP', 'Run',
         [
              'Cache'   => 86400,
              'Service' => 'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL',
              'Method' => 'GetLatestDateTime',
              'Call' => []
         ])['GetLatestDateTimeResult'];

        do
        {
            $Result = simplexml_load_string(F::Run('Code.Run.SOAP', 'Run',
                [
                    'Cache'   => 86400,
                    'Service' => 'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL',
                    'Method' => 'GetCursOnDate',
                    'Call' =>
                        [
                            'On_date' => $LatestDate
                        ]
                ])['GetCursOnDateResult']['any'])->ValuteData->ValuteCursOnDate;
        }
        while ($Result === null);

        foreach ($Result as $Currency)
            $Rates[(string) $Currency->VchCode] = (float) $Currency->Vcurs/ (int)$Currency->Vnom;

        return $Rates[$Call['Currency']];
    });