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
                if (isset($Nodes[$Index]))
                    $Data[$Name][$Key] = $Nodes[$Index];

        foreach ($Data as &$Node)
        {
            $Controls = F::Run('Entity.Nodes.Type.'.$Node['Type'], 'Widget', $Call);

            if (isset($Node['Control']))
                $Node['Widgets'] = $Controls[$Node['Control']];
            else
                $Node['Widgets'] = $Controls['Normal'];

            if (isset($Node['Link']))
            {
                $Node['Type'] = 'Complex.One2One';
                $Node['Widgets']['Write'] =
                    array (
                        'Type' => 'Form.Select',
                        'Options' => array
                            (
                                'Service' => 'Entity.Dict',
                                'Method' => 'Get',
                                'Call' =>
                                array (
                                    'Entity' => $Node['Link']['Entity'],
                                    'Key' => $Node['Link']['Key']
                                )
                        )
                );

                $Node['Widgets']['Filter'] =
                    array (
                        'Type' => 'Form.Select',
                        'Options' => array
                            (
                                'Service' => 'Entity.Dict',
                                'Method' => 'Get',
                                'Call' =>
                                array (
                                    'Entity' => $Node['Link']['Entity'],
                                    'Key' => $Node['Link']['Key']
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