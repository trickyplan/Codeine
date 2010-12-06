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

            if (is_array($Model))
            {
                $Call['Items'] = array();

                $Call['Items']['Form'] = array(
                     'UI'        => 'Form',
                     'Purpose'   => 'Create',
                     'Entity'    => $Call['Entity'],
                     'Plugin'    => $Call['F'],
                     'Data'      => $Model);
            }
            else
                Code::On(__CLASS__, 'errCreateAppModelNotLoaded', $Call);
        }

        return $Call;
    });