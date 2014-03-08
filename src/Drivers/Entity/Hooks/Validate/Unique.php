<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Node']['Unique']) && $Call['Node']['Unique'] && isset($Call['Data'][$Call['Name']]))
        {
            if ($Call['Name'] !== 'ID')
                $Where = [
                      $Call['Name'] => $Call['Data'][$Call['Name']],
                      'ID' => ['$ne' => $Call['Data']['ID']]
                   ];
            else
                $Where = [
                      'ID' => $Call['Data']['ID']
                   ];

            if (F::Run('Entity', 'Count',
                          [
                               'Entity' => $Call['Entity'],
                               'Where' => $Where
                          ]) > 0)
            {
                F::Log('Non-Unique '.$Call['Name'], LOG_ERR);
                return 'Unique';
            }
        }

        return true;
    });