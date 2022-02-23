<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Before', function ($Call)
    {
        $Call['Where'] = F::Live($Call['Where'], $Call);
        $Call['Current'] = F::Run('Entity', 'Read', $Call, ['One' => true]);
        return $Call;
    });

    setFn('Do', function ($Call)
    {
        $Call['Where'] = F::Live($Call['Where'], $Call);
        return F::Hook('afterUpdateDo',
                    F::Run(null, $Call['HTTP']['Method'],
                        F::Hook('beforeUpdateDo', $Call)));
    });

    setFn('GET', function ($Call)
    {
        // Загрузить предопределённые данные и умолчания
        if (empty($Call['Current']))
            $Call = F::Hook('onEntityUpdateNotFound', $Call);
        else
        {
            $Call['Data'] = $Call['Current'];

            $Call = F::Hook('beforeUpdateGet', $Call);
    
            $Call['Output']['Content']['Form Widget'] = ['Type' => 'Form', 'ID' => 'Update', 'Widget Template' => 'Form/'.$Call['Form']['Template']];
    
            $Call['Tag'] = isset($Call['Scope'])? $Call['Scope']: null;
            $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];
    
            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Scope'],
                    'ID' => isset($Call['Custom Layouts']['Update'])?
                            $Call['Custom Layouts']['Update']: 'Update',
                    'Context' => $Call['Context']
                ];
            
            if (empty($Call['Action']))
                $Call['Action'] = $Call['HTTP']['URI'];
            
            $Call['Output']['Content']['Form Widget']['Action'] = $Call['Action'];
            $Call['Output']['Content']['Form Widget']['Data']   = $Call['Data'];
    
            $Call = F::Apply('Entity.Form', 'Generate', $Call);

            $Call = F::Hook('afterUpdateGet', $Call);
        }

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeUpdatePost', $Call);

            if (isset($Call['Request']['Data']))
                $Call['Data'] = $Call['Request']['Data'];

            // Отправляем в Entity.Update
            $Result = F::Run('Entity', 'Update', $Call, ['One' => true]);

            // Выводим результат
            if (!isset($Result['Errors']) or empty($Result['Errors']))
            {
                $Call['Current'] = $Result;
                $Call = F::Hook('afterUpdatePost', $Call);
                $Call['Output']['Message'][] =
                    [
                        'Type' => 'Block',
                        'Class' => 'alert alert-success',
                        'Value' => '<codeine-locale>(.*)</codeine-locale>'
                    ];
            }
            else
            {
                foreach ($Result['Errors'] as $Name => $Errors)
                    foreach ($Errors as $Error)
                        $Call['Output']['Message'][] =
                            [
                                'Type'  => 'Template',
                                'Scope' => 'Entity/Validate',
                                'ID'    => $Error['Validator'],
                                'Data'  => $Error
                            ];
            }

            $Call = F::Apply(null, 'GET', $Call);

        return $Call;
    });