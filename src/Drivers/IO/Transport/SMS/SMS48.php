<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        return file_get_contents('http://sms48.ru/get_balance?login='.$Call['Login'].'&check='.md5($Call['Login'].md5($Call['Password'])));
    });

    setFn('Write', function ($Call)
    {
       $Call['Scope'] = (int) filter_var($Call['Scope'], FILTER_SANITIZE_NUMBER_INT);
       $Message = iconv('utf-8', 'windows-1251', $Call['Data']);
       $CheckSum = md5($Call['Username'].md5($Call['Password']).$Call['Scope']);	//Контрольная сумма (самый идиотский способ, что я видел)

       return file_get_contents($Call['SMS48'].'?'.http_build_query(
           [
            'login'     => $Call['Username'],
            'to'        => $Call['Scope'],
            'from'      => $Call['From'],
            'check2'    => $CheckSum,
            'dlr_url'   => $Call['HTTP']['URL'],
            'msg'       => $Message
           ],null, '&', PHP_QUERY_RFC3986
       ));
    });