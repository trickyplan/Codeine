<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Index', function ($Call)
    {
        $Model = F::loadOptions($Call['Entity'].'.Entity');
        $Call = F::loadOptions('IO', null, $Call);

        foreach($Model['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Index']) && $Node['Index'])
                F::Run('IO', 'Execute', $Call,
                    [
                        'Storage'   => $Model['Storage'],
                        'Execute'   => 'Create Index',
                        'Node'      => $Name
                    ]);
        }

        return $Call;
    });