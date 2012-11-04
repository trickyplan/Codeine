<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Read', function ($Call)
    {
        if (isset($Call['Cache']))
        {
            $Result = F::Run('IO', 'Read',
            [
                'Storage' => $Call['Cache'],
                'Scope' => $Call['Scope'],
                'Where' => sha1(serialize($Call['Where']).$Call['Storage'].$Call['Limit'])
            ]);

            if ($Result !== null)
                $Call['Result'] = $Result;

            return $Call;
        }
        else
            return $Call;
    });

    setFn('Write', function ($Call)
    {
        if (isset($Call['Cache']))
            F::Run('IO', 'Write',
            [
                'Storage' => $Call['Cache'],
                'Scope' => $Call['Scope'],
                'Where' => sha1(serialize($Call['Where']).$Call['Storage'].$Call['Limit']),
                'Data' => $Call['Result']
            ]);

        return $Call;
    });