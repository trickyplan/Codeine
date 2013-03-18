<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        foreach ($Call['Data'] as $Element)
            foreach ($Call['Nodes'] as $Name => $Node)
                foreach ($Call['Validators'] as $Validator)
                {
                    $Error = F::Run('Entity.Validate.'.$Validator, 'Process',
                        [
                            'Entity' => $Call['Entity'],
                            'Name' => $Name,
                            'Node' => $Node,
                            'Data' => $Element
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