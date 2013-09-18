<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    setFn('Do', function ($Call)
    {
        foreach ($Call['Notify'] as &$String)
            if (preg_match_all('@\$([\.\w]+)@', $String, $Vars))
            {
                foreach ($Vars[0] as $IX => $Key)
                    $String = str_replace($Key, F::Dot($Call,$Vars[1][$IX]) , $String);
            }

        F::Run('Entity', 'Create',
            [
                'Entity' => 'Notify',
                'One' => true,
                'Data' => $Call['Notify']
            ]);

        if (isset($Call['Notify']['SMS']) && $Call['Notify']['SMS'])
        {
            $Target = F::Run('Entity', 'Read', ['Entity' => 'User', 'Where' => $Call['Notify']['User'], 'One' => true]);
            F::Run('IO', 'Write', $Call, ['Storage' => 'SMS', 'Scope' => $Target['Phone']['Cellular'], 'Data' => $Call['Notify']['Title']]);
        }

        unset($Call['Notify']);

        return $Call;
    });