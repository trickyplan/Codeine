<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Index = [];

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Index']) && $Node['Index'])
            {
                $Call['Value'] = F::Dot($Call['Data'], $Name);

                if (is_scalar($Call['Value']))
                {
                    $Call = F::Hook('beforeTokenize', $Call);
                        $Call['Words'] = preg_split('/[\W]+/', $Call['Value']);
                    $Call = F::Hook('afterTokenize', $Call);

                    $Index = array_merge($Index, $Call['Words']);
                }
            }
        }

        $Index = array_unique($Index);
        sort($Index);
        return $Index;
    });