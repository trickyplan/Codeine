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
        $Call['Layouts'][] = 'Entity/'.$Call['Entity'];
        
        $Call['Data'] = Data::Read('Default::POST');

        if (isset($Call['Data']) && !empty($Call['Data']))
        {
            $Call['ID'] = uniqid();

            if(Code::Run(array_merge($Call,array(
                    'N' => 'Data.Model.Engine',
                    'F' => 'Create',
                    'D' => 'Engine'))))
                Code::On('Entity.Create.Object.Created', $Call);
                
        }

        $Model = Data::Read('Model::'.$Call['Entity']);

        if (is_array($Model))
        {
            $Call['Items'] = array();

            $Call['Items']['Form'] = array(
                 'UI'        => 'Form',
                 'Purpose'   => 'Create',
                 'Entity'    => $Call['Entity'],
                 'Plugin'    => $Call['F'],
                 'Model'      => $Model);
        }
        else
            Code::On('Entity.Create.Model.NotLoaded', $Call);

        

        return $Call;
    });
