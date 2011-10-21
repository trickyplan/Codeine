<?php

    /* Codeine
     * @author BreathLess
     * @description Redis Test
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Open', function ($Call)
     {
          return (is_object(F::Run(
                            array(
                                'Data' => array('Open', 'Redis'))
                        )));
     });

    self::Fn('Create', function ($Call)
    {
        // /Drivers/Data/Store/KeyValue/Redis.php
        return F::Run(
            array(
                'Data' => array('Create', 'Redis'),
                'ID' => 'Test',
                'Value' =>
                    json_encode(array(
                        'Key' => time()
                    )) 
            ));
    });

    self::Fn('Read', function ($Call)
    {
        return json_decode(F::Run(
                    array(
                        'Data' => array('Read', 'Redis'),
                        'ID' => 'Test'
                    )), true) ;
    });

    self::Fn('Update', function ($Call)
    {
        return F::Run(
                    array(
                        'Data' => array('Update', 'Redis'),
                        'ID' => 'Test',
                        'Value' =>
                            json_encode(array(
                                'Key' => time()
                            ))
                    ));
    });