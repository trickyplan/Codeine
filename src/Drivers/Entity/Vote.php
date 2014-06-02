<?php

    /*
     * @author BreathLess
     * @description Create Entity
     * @package Winebase
     * @version 7.0
     */

    setFn('Do', function ($Call)
    {
        if ($Call['HTTP']['Method'] == 'POST')
        {
            if (isset($Call['Session']['User']['ID']))
            {
                $UID = $Call['Session']['User']['ID'];

                $Decision = true;

                if (F::Run('Entity', 'Count',
                        [
                            'Entity' => 'Vote',
                            'Where'  =>
                            [
                                'Type' => $Call['Type'],
                                'Object' => $Call['Object'],
                                'User'   => $UID
                            ]
                        ]) > 0
                )
                    $Decision = false;

                $Entity = F::Run('Entity', 'Read',
                [
                    'Entity' => $Call['Type'],
                    'Where'  => $Call['Object'],
                    'One'    => true
                ]);

                if (isset($Call['Voting']['Can feel myself'])
                    || !$Call['Voting']['Can feel myself']
                    && $UID == $Entity['User'])
                    $Decision = false;

                if ($Decision)
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

        F::Run('Entity', 'Update', $Call,
            [
                'Entity' => $Call['Type'],
                'Where'  => $Call['Object']
            ]);

        $Call['Output']['Content'][] =
            F::Run('Entity', 'Far',
                [
                    'Entity' => $Call['Type'],
                    'Time' => microtime(true),
                    'Where' => $Call['Object'],
                    'Key' => $Call['Direction']
                ]);

        return $Call;
    });