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

        return ($User[0]['Password'] == F::Live($Call['Challenger'], array('Value' => $Call['Request']['Password'])));
    });

    self::setFn('Challenge', function ($Call)
    {
        return F::Live($Call['Challenger'], $Call);
    });