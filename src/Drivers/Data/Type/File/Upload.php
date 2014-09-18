<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Data'] = false;
        $Call['Scope'] = $Call['Entity'].'/'.$Call['Name'];

        $Call['ID'] = F::Run('Security.UID', 'Get', ['Mode' => 'Secure']);

        if (is_uploaded_file($Call['Value']))
            $Call['Data'] = file_get_contents($Call['Value']);
        elseif (preg_match('/^https?:\/\//', $Call['Value']))
        {
            $Call['Value'] = html_entity_decode($Call['Value']);
            $Web = F::Run('IO', 'Read', ['Storage' => 'Web', 'Where' => ['ID' => $Call['Value']]]);
            $Call['Data'] = array_pop($Web);
        }

        if ($Call['Data'])
        {
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