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

            // F::Log('Checking inheritance for *'.$Name.'*', LOG_INFO);
            // F::Log('Value is *'.j(F::Dot($Call['Data'], $Name)).'*', LOG_INFO);
            // F::Log('Is empty: *'.j(empty(F::Dot($Call['Data'], $Name))).'*', LOG_INFO);
           
            $Value = F::Dot($Call['Data'], $Name);
            
            if (isset($Node['Type']))
                $Value = F::Run('Data.Type.'.$Node['Type'], 'Write', ['Name' => $Name, 'Node' => $Node, 'Value' => $Value]);
            
            if (empty($Value))
            {
                if (isset($Node['Inherited']))
                {
                    F::Log('Inheritance enabled for *'.$Name.'* from *'.$Node['Inherited'].'*', LOG_DEBUG);
                    $Parent = F::Dot($Call['Data'], $Node['Inherited']);
                    
                    if (empty($Parent))
                        F::Log('Empty Inherited Field', LOG_DEBUG);
                    else
                    {
                        if (isset($Call['Data']['ID']) && $Parent == $Call['Data']['ID'])
                            F::Log('Inheritance Loop Protected', LOG_DEBUG); // FIXME Make Tracer
                        else
                        {
                            $Parent = F::Run('Entity', 'Read',
                            [
                                'Entity'    => $Call['Entity'],
                                'Where'     => F::Dot($Call['Data'], $Node['Inherited']),
                                'One'       => true,
                                'Fields'    => ['ID', $Name, $Node['Inherited']]
                            ]);

                            $Call['Data'] = F::Dot($Call['Data'], $Name, F::Dot($Parent, $Name)); // FIXME Add flag
                            F::Log('*'.$Name.'* node inherited from *'.$Parent['ID'].'* as '.j(F::Dot($Parent, $Name)), LOG_DEBUG);
                        }
                    }
                }
            }
        }
        return $Call;
    });