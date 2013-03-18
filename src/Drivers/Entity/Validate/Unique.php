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
            if (F::Run('Entity', 'Count',
                          [
                               'Entity' => $Call['Entity'],
                               'Where' =>
                                   [
                                      $Call['Name'] => $Call['Data'][$Call['Name']],
                                      'ID' => ['$ne' => $Call['Data']['ID']]
                                   ]
                          ]) > 0)
            {
                d(__FILE__, __LINE__, $Call['Data'][$Call['Name']]);
                return 'Unique';
            }
        }
        return true;
    });