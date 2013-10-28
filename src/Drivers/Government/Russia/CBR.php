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
             'Service' => 'http://www.cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx?WSDL',
             'Method' => 'BicToIntCode',
             'Call' => ['BicCode' => $Call['Value']]
         ])->BicToIntCodeResult;

        $Info = simplexml_load_string(F::Run('Code.Run.SOAP', 'Run',
         [
             'Service' => 'http://www.cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx?WSDL',
             'Method' => 'CreditInfoByIntCodeXML',
             'Call' => ['InternalCode' => $InternalCode]
         ])->CreditInfoByIntCodeXMLResult->any);

        return $Info->CO->OrgFullName;
    });

    setFn('GetRates', function ($Call)
    {
        $Rates = [];
        $Result = simplexml_load_string(F::Run('Code.Run.SOAP', 'Run',
        [
             'Service' => 'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL',
             'Method' => 'GetCursOnDate',
             'Call' =>
             [
                 'On_date' => date('c')
             ]
        ])->GetCursOnDateResult->any)->ValuteData->ValuteCursOnDate;

        foreach ($Result as $Currency)
            $Rates[(string) $Currency->VchCode] = (float) $Currency->Vcurs/ (int)$Currency->Vnom;

        return $Rates[$Call['Currency']];
    });