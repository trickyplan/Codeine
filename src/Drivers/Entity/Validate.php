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
        {
            foreach ($Call['Validators'] as $Validator)
                if (($Error = F::Run('Entity.Validate.'.$Validator, 'Process',
                    [
                    'Entity' => $Call['Entity'],
                    'Name' => $Name,
                    'Node' => $Node,
                    'Data' => $Call['Data']]
                )) !== true)
                {
                    $Call['Errors'][$Name][] = $Error;
                    F::Log('EV: '.$Name.' '.$Error, LOG_ERR);
                }
        }

        if (!empty($Call['Errors']))
            $Call['Failure'] = true;

        return $Call;
    });