<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Write', function ($Call)
    {
        if (isset($Call['Value']))
        {
            $Call['Value'] = F::Live($Call['Value']);

            if (!empty($Call['Value']))
            {
                F::Run('Entity', 'Delete', array(
                                        'Entity' => $Call['Entity'].'2'.$Call['Name'],
                                        'Where' =>
                                            [
                                                $Call['Entity'] => $Call['Data']['ID']
                                            ]
                                   ));

                foreach ($Call['Value'] as $Value)
                    F::Run('Entity', 'Create',
                        [
                            'Entity' => $Call['Entity'].'2'.$Call['Name'],
                            'Data' =>
                                [
                                     $Call['Name'] => $Value,
                                     $Call['Entity'] => $Call['Data']['ID']
                                ]
                       ]);
            }
        }

        return null;
    });

    setFn('Read', function ($Call)
    {
        return function() use ($Call) {
            $Data = F::Run('Entity', 'Read', array (
                                        'Entity' => $Call['Entity'].'2'.$Call['Name'],
                                        'Where' =>
                                            array (
                                                $Call['Entity'] => $Call['Data']['ID']
                                            )
                                   ));
            $Result = [];

            if(is_array($Data))
                foreach ($Data as $Row)
                    $Result[] = $Row[$Call['Name']];

            return $Result;
        };
    });