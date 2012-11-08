<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Node']['Unique']) && $Call['Node']['Unique'])
        {
            if (F::Run('Entity', 'Count',
                        [
                             'Entity' => $Call['Entity'],
                             'Where' =>
                                 [
                                    $Call['Name'] => $Call['Data'][$Call['Name']]
                                 ]
                        ]) > 0)
                return 'Unique';
        }
        return true;
    });