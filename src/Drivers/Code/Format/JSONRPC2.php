<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.1
     * @date 13.08.11
     * @time 22:46
     */

    self::setFn('DecodeRequest', function ($Call)
    {
        $Call['Value'] = json_decode($Call['Value'], true);

        if (isset($Call['Value']['params']))
            $Data = $Call['Value']['params'];
        else
            $Data = null;

        if (mb_strpos( $Call['Value']['method'], ':') !== false)
            list ($Data['_N'], $Data['_F']) = explode(':', $Call['Value']['method']);
        else
            $Data = null;

        return $Data;
    });

    self::setFn('DecodeResponse', function ($Call)
    {
        $Result = json_decode($Call['Value'], true);
        return $Result['result'];
    });

    self::setFn('EncodeResponse', function ($Call)
    {
        return json_encode(array(
                               'jsonrpc' => '2.0',
                               'result' => $Call['Value']
                           ));
    });

    self::setFn('EncodeRequest', function ($Call)
    {
        return json_encode(
            array(
                'jsonrpc' => '2.0',
                'method' => $Call['Value']['_N'].':'.$Call['Value']['_F'],
                'params' => $Call['Value']
            )
        );
    });