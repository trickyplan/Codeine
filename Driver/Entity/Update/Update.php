<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Update Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 20:56
     */

    self::Fn('Update', function ($Call)
    {
        if (isset($Call['Data']) && !empty($Call['Data']))
        {
            if (Code::Run(
                array(
                    'N' => 'Data.Model',
                    'F' => 'Validate',
                    'Data' => $Call['Data'] )))
            {
                Code::Run(
                    array(
                        'N' => 'Data.Model',
                        'F' => 'Update',
                        'ID' => $Call['ID'],
                        'Entity'    => $Call['Entity'],
                        'Data' => $Call['Data'] ));
            }
            Code::On('App.Create.Object.Updated', $Call);
        }

        $Data =
            Data::Read($Call['Entity'].'::'.$Call['ID']);
        
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
                 'Model'     => $Model,
                 'Data'      => $Data[$Call['ID']]);
        }
        else
            Code::On('App.Create.Model.NotLoaded', $Call);

        

        return $Call;
    });
