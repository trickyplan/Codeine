<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Data'] = false;
        $Call['Scope'] = strtr($Call['Entity'], '.', '/').'/'.$Call['Name'];

        if (is_uploaded_file($Call['Value']))
            $Call['Data'] = file_get_contents($Call['Value']);
        elseif (preg_match('/^https?:\/\//', $Call['Value']))
        {
            $Call['Value'] = html_entity_decode($Call['Value']);
            $Web = F::Run('IO', 'Read', ['Storage' => 'Web', 'Where' => ['ID' => $Call['Value']]]);
            $Call['Data'] = array_pop($Web);
        }
        else
            F::Log('Unknown file data for '.$Call['Name'], LOG_ERR);

        // Если нет новых данных
        if ($Call['Data'] === false)
            ;
        else
        {
            // Получить новый ID
            $Call['ID'] = F::Run('Security.UID', 'Get', ['Mode' => 'Secure']);
            // Получить новое имя
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