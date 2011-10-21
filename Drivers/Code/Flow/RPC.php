<?php

    /* Codeine
     * @author BreathLess
     * @description: RPC Caller
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 8:50
     */

    self::Fn('Run', function ($Call)
    {
        // TODO Chain Call
        $Decoded = F::Run(
            array(
                '_N' => 'Code.Format.'.$Call['Method'],
                '_F' => 'DecodeRequest',
                'Value' => $Call['Value']
                 ));

       if ($Decoded === null)
           $Result = array('error' => 'Invalid input');
       else
           $Result = F::Run($Decoded);

       return F::Run(
            array(
                '_N' => 'Code.Format.'.$Call['Method'],
                '_F' => 'EncodeResponse',
                'Value' => $Result
                 ));
    });
