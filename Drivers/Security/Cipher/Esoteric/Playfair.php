<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 5:16
     */

    self::Fn('Crypt', function ($Call)
    {
        return F::Run(array(
                    '_N'  => 'Engine.Message',
                    '_F'  => 'Send',
                    'To' => 'Event',
                    'Message' => 'Call to unrealized function',
                    'Call' => $Call
                       ));
    });

    self::Fn('Decrypt', function ($Call)
    {
        return F::Run(array(
                    '_N'  => 'Engine.Message',
                    '_F'  => 'Send',
                    'To' => 'Event',
                    'Message' => 'Call to unrealized function',
                    'Call' => $Call
                       ));
    });
