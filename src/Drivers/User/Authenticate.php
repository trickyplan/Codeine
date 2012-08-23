<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Run('Security.Auth.System.'.$Call['Mode'], 'Authenticate', $Call);

        if (!empty($Call['User']))
        {
            if ($Call['User']['Status'] >= 1)
            {
                if (isset($Call['Request']['Remember']))
                    $Call['TTL'] = $Call['TTLs']['Long'];

                $Call['Session'] = F::Run('Security.Auth', 'Attach', $Call,
                    array('User' => $Call['User']['ID']));

                $Call = F::Hook('Authentification.Success', $Call);
                return $Call;

            }
            else
            {
                if ($Call['User']['Status'] == -1)
                {
                    $Call['User']['Server'] = $_SERVER['HTTP_HOST'];

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
        {
             $Call = F::Hook('Authentification.Failed', $Call);
        }

        return $Call;
    });