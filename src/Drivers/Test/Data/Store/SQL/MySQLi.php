<?php

    /* Codeine
     * @author BreathLess
     * @description MySQL Test
     * @package Codeine
     * @version 6.0
     * @see /Drivers/Data/Store/SQL/MySQL.php
     */

     self::Fn('Open', function ($Call)
     {
          return (is_object(F::Run(
                            array(
                                'Data' => array('Open', 'MySQL')
                            )
                        )));
     });

    self::Fn('Create', function ($Call)
    {
        return F::Run(
            array(
                'Data' => array('Create', 'MySQL'),
                'ID' => 'Test',
                'Value' =>
                    json_encode(array(
                        'Key' => time()
                    )) 
            ));
    });

    self::Fn('Find', function ($Call)
    {
        return F::Run(
                    array(
                        'Data' => array('Find', 'MySQL'),
                        'Scope' => 'Page',
                        'Where' => array(
                            'Title' => 'О проекте'
                        )
                    ));
    });

    self::Fn('Read', function ($Call)
    {
        return F::Run(
                    array(
                        'Data' => array('Read', 'MySQL'),
                        'Scope' => 'Page',
                        'ID' => 'About'
                    ));
    });

    self::Fn('Read', function ($Call)
    {
        return F::Run(
                    array(
                        'Data' => array('Create', 'MySQL'),
                        'Scope' => 'Session',
                        'ID' => 'RR',
                        'Value' =>
                            array(
                                array('K' => 'Key', 'V' => 'Value'),
                                array('K' => 'Key2', 'V' => 40),
                            )
                    ));
    });

    self::Fn('Update', function ($Call)
    {
        return F::Run(
                    array(
                        'Data' => array('Update', 'MySQL'),
                        'Scope' => 'Page',
                        'Where' =>
                            array(
                                'ID' => 'About'
                            ),
                        'Set' =>
                            array(
                                'CreatedOn' => time()
                            )
                    ));
    });