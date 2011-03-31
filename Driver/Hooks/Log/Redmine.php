<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 31.03.11
     * @time 1:12
     */

    self::Fn('Catch', function ($Call)
    {
        return Data::Create(
          array(
              'Point' => 'HTTP',
              'ID' => 'http://localhost/redmine/issues.json',
              'user:pass' => 'breathless:montenegro',
              'Data' => json_encode(array(
                    'issue' => array(
                        'project_id' => 'codeine',
                        'subject' => 'subject',
                        'notes' => 'notes'
                    )
                )),
              'Headers' => array('Content-Type:application/json')
          )
        );
    });
