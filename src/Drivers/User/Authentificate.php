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

            if ($User[0]['Status'] == 1)
            {
                if (isset($Call['Request']['TTL']))
                    $Call['TTL'] = $Call['Request']['TTL'];

                F::Run('Security.Auth', 'Attach', $Call, array('User' => $User[0]['ID'], 'TTL' => $Call['TTLs'][$Call['TTL']]));

                $Call['Output']['Content'][]
                    = array(
                    'Type' => 'Block',
                    'Class' => 'alert alert-success',
                    'Value' => 'Access granted'
                );
            }
            else
                $Call['Output']['Content'][]
                    = array(
                    'Type' => 'Block',
                    'Class' => 'alert alert-warning',
                    'Value' => 'User not activated'
                );

        }
        else
            $Call['Output']['Content'][]
                = array(
                'Type' => 'Block',
                'Class' => 'alert alert-danger',
                'Value' => 'Access denied'
            );

        return $Call;
    });