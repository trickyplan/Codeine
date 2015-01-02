<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Node']['Unique']) && $Call['Node']['Unique'] && isset($Call['Data'][$Call['Name']]))
        {
            if ($Call['Name'] !== 'ID')
            {
                $Where = [
                    $Call['Name'] => $Call['Data'][$Call['Name']],
                    'ID' => ['$ne' => $Call['Data']['ID']]
                ];
                $Limit = 0;
            }
            else
            {
                if ($Call['Purpose'] == 'Create')
                    $Limit = 0;
                else
                    $Limit = 1;

                $Where = [
                    'ID' => $Call['Data']['ID']
                ];
            }

            if (F::Run('Entity', 'Count',
                          [
                               'Entity' => $Call['Entity'],
                               'Where' => $Where
                          ]) > $Limit)
            {
                F::Log('Non-unique '.$Call['Name'].' value: '.j($Call['Data'][$Call['Name']]), LOG_ERR);
                F::Log($Call['Data'], LOG_WARNING);
                return 'Unique';
            }
        }

        return true;
    });
