<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    setFn('GET', function ($Call)
    {
        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Run('Security.Auth.System.'.$Call['Mode'], 'Authenticate', $Call);

        if (!empty($Call['User']))
        {
            if ($Call['User']['Status'] >= 1)
            {
                if (isset($Call['Request']['Remember']))
                    $Call['TTL'] = $Call['TTLs']['Long'];

                $Call['Session'] = F::Run('Security.Auth', 'Attach', $Call,
                    ['User' => $Call['User']['ID']]);

                $Call = F::Hook('Authenticating.Success', $Call);
            }
            else
            {
                if ($Call['User']['Status'] == -1)
                {
                    $Call['User']['Server'] = $Call['Host'];

                    $Call['Output']['Content'][]
                        = array(
                        'Type' => 'Template',
                        'Scope' => 'User',
                        'ID' => 'Banned',
                        'Data' => $Call['User']
                    );
                }
                else
                {
                    list(,$Call['User']['Server']) = explode('@', $Call['User']['EMail']);

                    $Call['Output']['Content'][]
                        = array(
                            'Type' => 'Template',
                            'Scope' => 'User',
                            'ID' => 'Activation/Needed',
                            'Data' => $Call['User']
                        );
                }
            }

        }
        else
             $Call = F::Hook('Authenticating.Failed', $Call);

        return $Call;
    });