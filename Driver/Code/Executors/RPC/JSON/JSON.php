<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: JSON RPC Caller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 27.03.11
     * @time 22:59
     */

    self::Fn('Run', function ($Call)
    {
        $Response = Data::Create(
          array(
              'Point' => 'HTTP',
              'ID' => $Call['Call']['N'],
              'Data' => json_encode(
                              array(
                                    'method' => $Call['Call']['F'],
                                    'params' => $Call['Call']
                                ))
          )
        );

        return json_decode($Response['Result']);
    });
