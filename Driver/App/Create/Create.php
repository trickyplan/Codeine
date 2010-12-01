<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Create Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 20:56
     */

    self::Fn('Create', function ($Call)
    {

            {
                $Model = Data::Read(
                        array(
                            'Point' => 'Model',
                            'Where' =>
                                array(
                                    'ID'=>$Call['Entity'])));

                $Items = array();        
                $Items['Form'] = array(
                     'UI'        => 'Form',
                     'Purpose'   => 'Create',
                     'Entity'    => $Call['Entity'],
                     'Plugin'    => $Call['Function'],
                     'Data'      => $Model);
            }

        return $Items;
    });