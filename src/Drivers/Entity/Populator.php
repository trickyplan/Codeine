<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Form', function ($Call)
    {
        $Call['Entity'] = $Call['Request']['Entity'];
        $Call = F::Run('Entity', 'Load', $Call);

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            $Name = strtr($Name, '.', '_');

            if (isset($Node['Populator']))
                $Data[$Name] = F::Live($Node['Populator']);
            else
                if (isset($Node['Type']))
                    $Data[$Name] = F::Run('Data.Type.'.$Node['Type'], 'Populate', ['Node' => $Node]);

            if (isset($Node['Examples']))
                $Data[$Name] = $Node['Examples'][array_rand($Node['Examples'])];

            if ($Data[$Name] === null)
                unset($Data[$Name]);
        }

        $Call['Output']['Content'][] = $Data;

        $Call['Renderer'] = 'View.JSON';
        return $Call;
    });