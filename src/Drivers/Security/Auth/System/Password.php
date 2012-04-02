<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Identificate', function ($Call)
    {
        return $Call;
    });

    self::setFn('Authentificate', function ($Call)
    {
        $User = F::Run('Entity', 'Read',
                     array(
                          'Entity' => 'User',
                          'Where' =>
                              array(
                                  'Login' => $Call['Request']['Login']
                              )
                     ));

        if (empty($User))
        {

        }

        if ($User[0]['Password'] == F::Live($Call['Challenger'], array('Value' => $Call['Request']['Password'])))
        {
            F::Run('Security.Auth', 'Attach', $Call, array('User' => $User[0]['ID']));

            $Call['Output']['Content'][]
                = array(
                'Type' => 'Block',
                'Class' => 'alert alert-success',
                'Value' => 'Password accepted'
            );
        }
        else
        {
            $Call['Output']['Content'][]
                = array(
                'Type' => 'Block',
                'Class' => 'alert alert-error',
                'Value' => 'Wrong password'
            );
        }

        return $Call;
    });