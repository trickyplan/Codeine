<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        $Names = $Call['Value']['Name'];
        unset($Call['Value']['Name']);

        foreach ($Names as $Index => $Name)
            foreach ($Call['Value'] as $Key => $Nodes)
                $Data[$Name][$Key] = $Nodes[$Index];

        foreach ($Data as &$Node)
        {
            if (strpos($Node['Type'],'Entity') !== false)
            {
                list(, $Entity) = explode('.',$Node['Type']);

                $Node['Type'] = 'Complex.One2One';
                $Node['Widgets']['Create'] =
                    array ('Type' => 'Form.Select',
                    'Value' => array (

                        'Service' => 'Entity.Dict',
                        'Method' => 'Get',
                        'Call' =>
                        array (
                            'Entity' => $Entity,
                            'Key' => 'Title'
                        )
                    )
                );
            }
        }
        return $Data;
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });