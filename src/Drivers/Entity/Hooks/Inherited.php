<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Default value support 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Call['Fields']) && !in_array($Name, $Call['Fields']))
                continue;

            if (F::Dot($Call['Data'], $Name) === null)
            {
                if (isset($Node['Inherited']))
                {
                    if (F::Dot($Call['Data'], $Node['Inherited']) == null)
                        F::Log('Empty Ancestor', LOG_INFO);
                    else
                    {
                        $Parent = F::Run('Entity', 'Read',
                        [
                            'Entity'    => $Call['Entity'],
                            'Where'     => F::Dot($Call['Data'], $Node['Inherited']),
                            'One'       => true,
                            'Fields'    => ['ID', $Name]
                        ]);

                        $Call['Data'] = F::Dot($Call['Data'], $Name, F::Dot($Parent, $Name)); // FIXME Add flag
                        F::Log($Name.' inherited from '.$Parent['ID'].' as '.j(F::Dot($Parent, $Name)), LOG_INFO);
                    }
                }
            }
        }

        return $Call;
    });