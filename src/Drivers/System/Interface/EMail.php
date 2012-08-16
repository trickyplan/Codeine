<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Output', function ($Call)
    {
        $Message = [];
        $Message['Scope'] = $Call['Recipient'];
        $Message['ID'] = $Call['Title'];
        $Message['Headers'] = array ('Content-type:' => ' text/html; charset="utf-8"');
        $Message['Data'] = $Call['Output'];

        F::Run('Code.Run.Delayed', 'Run', [
            'Run' => F::Merge($Call['Sender'], ['Call' => $Message])
        ]);

        echo 'Message sent';
        return $Call;
    });