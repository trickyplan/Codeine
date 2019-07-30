<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (F::Dot($Call, 'Node.Unique') && F::Dot($Call['Data'], $Call['Name']))
        {
            switch ($Mode = F::Dot($Call, 'Validation.Unique.Mode'))
            {
                case 'Create':
                    $Limit = 0;
                break;

                case 'Update':
                    $Limit = 1;
                break;
                default:
                    $Limit = 0;
                    F::Log('Incorrect Validation Unique Mode: '.$Mode, LOG_WARNING);
            }

            if ($Call['Name'] !== 'ID')
            {
                $Where = [
                    $Call['Name'] => $Call['Data'][$Call['Name']],
                    'ID' =>
                    [
                        '$ne' => $Call['Data']['ID']
                    ]
                ];
                $Limit = 0;
            }
            else
            {
                $Where =
                [
                    'ID' => $Call['Data']['ID']
                ];
            }

            F::Log('Checking unique *'.$Call['Name']
                .'* value *'
                .$Call['Data'][$Call['Name']]
                .'* ('.$Limit.')', LOG_INFO);

            $Count = F::Run('Entity', 'Count',
                          [
                               'Entity' => $Call['Entity'],
                               'Where' => $Where
                          ]);

            if ($Count > $Limit)
            {
                F::Log('Non-unique ('.$Count.'>'.$Limit.') '.$Call['Name'].' value: '.j($Call['Data'][$Call['Name']]).' ', LOG_NOTICE);
                return 'Unique';
            }
        }

        return true;
    });
