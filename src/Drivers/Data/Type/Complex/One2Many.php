<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Write', function ($Call)
    {
        F::Run('Entity', 'Delete', array(
                                    'Entity' => $Call['Entity'].'2'.$Call['Name'],
                                    'Where' =>
                                        array (
                                            $Call['Entity'].'ID' => $Call['Data']['ID']
                                        )
                               ));


        foreach ($Call['Value'] as $Value)
            F::Run('Entity', 'Create', array(
                                        'Entity' => $Call['Entity'].'2'.$Call['Name'],
                                        'Data' =>
                                            array (
                                                $Call['Name'].'ID' => $Value,
                                                $Call['Entity'].'ID' => $Call['Data']['ID']
                                            )
                                   ));

        return null;
    });

    self::setFn('Read', function ($Call)
    {
        $Data = F::Run('Entity', 'Read', array(
                                    'Entity' => $Call['Entity'].'2'.$Call['Name'],
                                    'Where' =>
                                        array (
                                            $Call['Entity'].'ID' => $Call['Data']['ID']
                                        )
                               ));
        $Result = array();

        foreach ($Data as $Row)
            $Result[] = $Row[$Call['Name'] . 'ID'];

        return $Result;
    });