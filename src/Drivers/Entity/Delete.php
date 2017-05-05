<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Before', function ($Call)
    {
        $Call = F::Hook('beforeDeleteBefore', $Call);

            $Call['Where'] = F::Live($Call['Where']);

            $Call['Data'] = F::Run('Entity', 'Read', $Call, ['One' => true, 'Limit' => ['From' => 0, 'To' => 1]]);

        $Call = F::Hook('afterDeleteBefore', $Call);
        return $Call;
    });
    
    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeDeleteDo', $Call);

            $Call = F::Apply(null, $Call['HTTP']['Method'], $Call);

        $Call = F::Hook('afterDeleteDo', $Call);

        return $Call;
    });
    
    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeDeleteGet', $Call);
        
        $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];

        if (empty($Call['Data']))
            $Call = F::Hook('onDeleteNotFound', $Call);
        else
        {
            $Call['Output']['Content'][] = array (
                'Type'  => 'Template',
                'Scope' => $Call['Scope'],
                'ID' => (isset($Call['Template'])? $Call['Template']: 'Delete'),
                'Data' => $Call['Data']
            );

            $Call = F::Hook('afterDelete', $Call);
        }
    
        F::Log($Call['Data'], LOG_DEBUG);
        
        $Call = F::Hook('afterDeleteGet', $Call);

        return $Call;
    });


    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeDeletePost', $Call);

            $Call['Data'] = F::Apply('Entity', 'Delete', $Call);

        $Call = F::Hook('afterDeletePost', $Call);

        return $Call;
    });