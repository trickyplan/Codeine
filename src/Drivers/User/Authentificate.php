<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        if (F::Run('Security.Auth.System.Password', 'Authentificate', $Call))
        {
            $User = F::Run('Entity', 'Read',
                     array(
                          'Entity' => 'User',
                          'Where' =>
                              array(
                                  'Login' => $Call['Request']['Login']
                              )
                     ));

            if ($User[0]['Status'] >= 1)
            {
                if (isset($Call['Request']['TTL']))
                    $Call['TTL'] = $Call['Request']['TTL'];

                F::Run('Security.Auth', 'Attach', $Call,
                    array('User' => $User[0]['ID'],
                          'TTL' => $Call['TTLs'][$Call['TTL']]));

                $Call = F::Hook('Authentification.Success', $Call);

                return $Call;

            }
            else
            {
                if ($User[0]['Status'] == -1)
                {
                    $User[0]['Server'] = $_SERVER['HTTP_HOST'];

                    $Call['Output']['Content'][]
                        = array(
                        'Type' => 'Template',
                        'Scope' => 'User',
                        'Value' => 'Banned',
                        'Data' => $User[0]
                    );
                }
                else
                {
                    list(,$User[0]['Server']) = explode('@', $User[0]['EMail']);

                    $Call['Output']['Content'][]
                        = array(
                        'Type' => 'Template',
                        'Scope' => 'User',
                        'Value' => 'Activation/Needed',
                        'Data' => $User[0]
                    );
                }
            }

        }
        else
        {
            $Call['Output']['Content'][]
                        = array(
                        'Type' => 'Template',
                        'Scope' => 'User',
                        'Value' => 'Incorrect'
                    );

            $Call = F::Hook('Authentification.Failed', $Call);

        }

        return $Call;
    });