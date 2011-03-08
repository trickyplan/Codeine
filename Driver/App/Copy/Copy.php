<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Copy Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 20:56
     */

    self::Fn('Copy', function ($Call)
    {
        if (isset($Call['ID']))
        {

            $Copy = Data::Read($Call['Entity'].'::'.$Call['ID']);
            $Call['Data'] = $Copy[$Call['ID']];
            $Call['ID'] = uniqid();

            if (Code::Run(
                array(
                    'N' => 'Data.Model',
                    'F' => 'Validate',
                    'Data' => $Call['Data'] )))
            {
                Code::Run(
                    array(
                        'N' => 'Data.Model',
                        'F' => 'Create',
                        'ID' => $Call['ID'],
                        'Entity'    => $Call['Entity'],
                        'Data' => $Call['Data'] ));
            }
            Code::On('App.Create.Object.Copied', $Call);
        }

        return $Call;
    });
