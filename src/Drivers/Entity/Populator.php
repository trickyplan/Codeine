<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Apply('Entity', 'Load', $Call);


        if (isset($Call['Where']))
        {
            $Call['Populate Count'] = $Call['Where'];
            unset($Call['Where']);
        }

        for ($IX = 0; $IX < $Call['Populate Count']; ++$IX)
        {
            $Data = ['Populated' => true];

            foreach ($Call['Nodes'] as $Name => $Node)
            {
                $Name = strtr($Name, '.', '_');

                if (isset($Node['Widgets']))
                {
                    if(isset($Node['Examples']))
                        $Node['Examples'] = F::Live($Node['Examples']);
                    else
                        $Node['Examples'] = [];

                    if (isset($Node['Populator']))
                        $Data[$Name] = F::Live($Node['Populator']);
                    else
                        if (isset($Node['Type']))
                            $Data[$Name] = F::Run('Data.Type.'.$Node['Type'], 'Populate',['Node' => $Node]);

                    if (!empty($Node['Examples']))
                        $Data[$Name] = $Node['Examples'][array_rand($Node['Examples'])];

                    if ($Data[$Name] === null)
                        unset($Data[$Name]);
                }
            }

            $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => $Call['Entity'],
                'ID' => 'Show/Short',
                'Data' => $Data
            ];
            
            F::Run('Entity', 'Create', $Call, ['One' => true, 'Data!' => $Data]);
        }

        return $Call;
    });