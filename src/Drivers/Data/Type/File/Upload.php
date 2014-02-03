<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Scope'] = $Call['Entity'].'/'.$Call['Name'];

        if (is_uploaded_file($Call['Value']) or preg_match('/^http:\/\//', $Call['Value']))
        {
            $Call['ID'] = F::Run('Security.UID', 'Get', $Call);

            $Call['Data'] = file_get_contents($Call['Value']);
            $Call['Name'] = F::Live($Call['Node']['Naming'], $Call);

            F::Run('IO', 'Write', $Call,
            [
                 'Storage' => $Call['Node']['Storage'],
                 'Where'   => $Call['Name']
            ]);

            return $Call['Name'];
        }

        return $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value'];
    });