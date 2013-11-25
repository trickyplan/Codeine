<?php

    /*
     * @author BreathLess
     * @description Create Entity
     * @package Winebase
     * @version 7.0
     */

    setFn('Do', function ($Call)
    {
        if ($Call['HTTP Method'] == 'POST')
        {
            if (isset($Call['Session']['User']['ID']))
            {
                $UID = $Call['Session']['User']['ID'];

                if (F::Run('Entity', 'Count',
                        [
                            'Entity' => 'Vote',
                            'Where'  =>
                            [
                                'Type' => $Call['Type'],
                                'Object' => $Call['Object'],
                                'User'   => $UID
                            ]
                        ]) == 0
                )
                {
                    F::Run('Entity', 'Create', $Call,
                        [
                            'Entity' => 'Vote',
                            'One'    => true,
                            'Data'   =>
                            [
                                'Type' => $Call['Type'],
                                'Object' => $Call['Object'],
                                'Direction'  => $Call['Direction'],
                                'User'   => $UID
                            ]
                        ]);
                }
            }
        }

        F::Run('Entity.Touch', 'Do', $Call,
            [
                'Entity' => $Call['Type'],
                'Where' => $Call['Object']
            ]);

        $Call['Output']['Content'] =
            F::Run('Entity', 'Far',
                [
                    'Entity' => $Call['Type'],
                    'ReRead' => true,
                    'Where' => $Call['Object'],
                    'Key' => $Call['Direction']
                ]);;

        return $Call;
    });