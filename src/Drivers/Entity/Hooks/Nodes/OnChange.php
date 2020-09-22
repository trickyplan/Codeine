<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Default value support 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        // Если модель определена
        if (isset($Call['Nodes']) and !isset($Call['Skip Live']))
        {
            if (isset($Call['Data']['ID']))
                F::Log('Processing changes for *'.$Call['Entity'].':'.$Call['Data']['ID'].'*', LOG_DEBUG);

            $Call['Nodes'] = F::Sort($Call['Nodes'], 'Weight', SORT_ASC);
            
            foreach ($Call['Nodes'] as $Name => $Node)
            {
                // Если у ноды определён нужный хук
                if (isset($Node['Hooks']) && isset($Node['Hooks']['OnChange']))
                {
                    if (F::Dot($Call, 'Current.'.$Name) !== F::Dot($Call, 'Data.'.$Name))
                    {
                        $Call = F::Live($Node['Hooks']['OnChange'],
                            $Call,
                            [
                                'Name' => $Name,
                                'Node' => $Node,
                                'Data' => $Call['Data']
                            ]);

                        F::Log(function () use ($Node, $Call) {return 'by '.j($Node['Hooks']['OnChange']);} , LOG_DEBUG);
                    }
                }
            }
        }

        return $Call;
    });