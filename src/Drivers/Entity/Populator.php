<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        $Data = [];

        for ($IX = 0; $IX < $Call['Populate Count']; ++$IX)
        {
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
                        $Data[$IX][$Name] = F::Live($Node['Populator']);
                    else
                        if (isset($Node['Type']))
                            $Data[$IX][$Name] = F::Run('Data.Type.'.$Node['Type'], 'Populate',['Node' => $Node]);

                    if (!empty($Node['Examples']))
                        $Data[$IX][$Name] = $Node['Examples'][array_rand($Node['Examples'])];

                    if ($Data[$IX][$Name] === null)
                        unset($Data[$IX][$Name]);
                }
            }

            $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => $Call['Entity'],
                'ID' => 'Show/Short',
                'Data' => $Data[$IX]
            ];
        }

        F::Run('Entity', 'Create', $Call, ['Data!' => $Data]);

        return $Call;
    });