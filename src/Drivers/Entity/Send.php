<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Message['Scope'] = $Call['To']; // FIXME
        $Message['ID']    = $Call['Subject'];

        $Message['Data']  = F::Run('View', 'LoadParsed', $Call,
                                             [
                                                  'Scope' => $Call['Entity'],
                                                  'ID' => 'Show/EMail'
                                             ]);

        $Message['Headers'] = array ('Content-type:' => ' text/html; charset="utf-8"');


        F::Run('Code.Run.Delayed', 'Run', [
            'Run' => F::Merge($Call['Sender'], ['Call' => $Message])
        ]);

        return $Call;
    });