<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
            foreach ($Call['Validators'] as $Validator)
            {
                $Error = F::Run('Entity.Hooks.Validate.'.$Validator, 'Process', $Call,
                    [
                        'Name' => $Name,
                        'Node' => $Node,
                        'Data' => $Call['Data']
                    ]);

                if ($Error !== true)
                {
                    $Call['Errors'][$Name][] = $Error;
                    F::Log($Name.' '.$Error, LOG_INFO);
                }
            }

        if (!empty($Call['Errors']))
            $Call['Failure'] = true;
        return $Call;
    });