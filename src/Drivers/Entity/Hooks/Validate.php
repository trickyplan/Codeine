<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
            foreach ($Call['Validators'] as $Validator)
            {
                $isValid = F::Run('Entity.Hooks.Validate.'.$Validator, 'Process', $Call,
                    [
                        'Name' => $Name,
                        'Node' => $Node,
                        'Data' => $Call['Data']
                    ]);

                if ($isValid !== true)
                {
                    $Call['Errors'][$Name][] = $isValid;
                    F::Log($Call['Entity'].':'.$Name.' '.$isValid, LOG_INFO);
                }
            }

        if (!empty($Call['Errors']))
            $Call['Failure'] = true;

        return $Call;
    });