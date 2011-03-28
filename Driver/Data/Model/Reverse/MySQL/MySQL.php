<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 28.03.11
     * @time 1:56
     */

    self::Fn('Generate', function ($Call)
    {
        $Fields = Data::Query(array(
            'Point' => 'Page',
            'Query' => 'DESCRIBE `'.$Call['Table'].'`'));

        $Model = array(
            'Title' => $Call['Table'],
            'Nodes' => array()
        );

        unset ($Fields[0]);
        
        foreach($Fields as $Field)
        {
            $Model['Nodes'][$Field['Field']] =
                array(
                    'Type' => 'String',
                    'Controls' => array(
                        'Create' => 'Textfield',
                        'Update' => 'Textfield'
                    )
                );
        }

        return $Model;
    });
