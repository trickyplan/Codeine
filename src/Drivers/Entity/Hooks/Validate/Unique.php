<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Node']['Unique']) && $Call['Node']['Unique'] && F::Dot($Call['Data'], $Call['Name']))
        {
            if (isset($Call['Where']['ID']))
                $Limit = 1;
            else
                $Limit = 0;
            
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
                .'* ('.$Call['Purpose'].')', LOG_INFO);

            $Count = F::Run('Entity', 'Count',
                          [
                               'Entity' => $Call['Entity'],
                               'Where' => $Where
                          ]);

            if ($Count > $Limit)
            {
                F::Log('Non-unique ('.$Count.'/'.$Limit.') '.$Call['Name'].' value: '.j($Call['Data'][$Call['Name']]).' ', LOG_NOTICE);
                return 'Unique';
            }
        }

        return true;
    });
