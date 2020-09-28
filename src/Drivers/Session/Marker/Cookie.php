<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 43.6.0
     */

    setFn ('Read', function ($Call)
    {
        if (PHP_SAPI == 'cli')
            return '1';

        return isset($Call['HTTP']['Cookie'][$Call['Marker']['Cookie']['Name']]) ? $Call['HTTP']['Cookie'][$Call['Marker']['Cookie']['Name']]: null;
    });

    setFn ('Write', function ($Call)
    {
        $Cookie = F::Run('IO','Write', $Call,
        [
            'Storage'   => 'Cookie',
            'Where'     =>
            [
                'ID'    => $Call['Marker']['Cookie']['Name']
            ],
            'Data'      => $Call['SID'],
            'Cookie'   =>
            [
                'TTL'       => $Call['Marker']['Cookie']['TTL'],
                'Path'      => $Call['Marker']['Cookie']['Path'],
                'Secure'    => $Call['Marker']['Cookie']['Secure'],
                'HTTP Only' => $Call['Marker']['Cookie']['HTTP Only'],
                'Same Site' => $Call['Marker']['Cookie']['Same Site']
            ]
        ]);

        if ($Call['Session']['Marker'] = $Cookie)
            $Call['HTTP']['Cookie'][$Call['Marker']['Cookie']['Name']] = $Call['SID'];
        else
            $Call = F::Hook('Cookie.Set.Failed', $Call);

        return $Call;
    });


    setFn('Destroy', function ($Call)
    {
        if (isset($Call['HTTP']['Cookie'][$Call['Marker']['Cookie']['Name']]))
            setcookie ($Call['Marker']['Cookie']['Name'], '');

        return $Call;
    });