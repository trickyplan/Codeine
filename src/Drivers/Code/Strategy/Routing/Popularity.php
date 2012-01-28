<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.0
     * @date 31.08.11
     * @time 6:21
     */

    self::setFn('List', function ($Call)
    {
        return F::Run(array(
                    'Message' => 'Event',
                    'Message' => 'Call to unrealized function',
                    'Call' => $Call
                       ));
    });
