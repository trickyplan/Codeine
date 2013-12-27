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
            $Where = [
                      $Call['Name'] => $Call['Data'][$Call['Name']],
                      'ID' => ['$ne' => $Call['Data']['ID']]
                   ];

            if (F::Run('Entity', 'Count',
                          [
                               'Entity' => $Call['Entity'],
                               'Where' => $Where
                          ]) > 0)
                return 'Unique';
        }

        return true;
    });