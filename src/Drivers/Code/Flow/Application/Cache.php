<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('beforeApplicationRun', function ($Call)
    {
        $Call['CID'] = sha1(json_encode($Call['Run']));

        $Cached = F::Run('IO', 'Read', ['Storage' => 'Application Cache', 'Where' => ['ID' => $Call['CID']]])[0];

        if (is_array($Cached) && $Cached['Expires'] > time())
            $Call['Run'] = ['Output' => $Cached['Result']];

        return $Call;
    });


    setFn('afterApplicationRun', function ($Call)
    {
        if (isset($Call['CID']) && isset($Call['Run']['TTL']))
        {
            F::Run('IO', 'Write',
                [
                    'Storage' => 'Application Cache',
                    'Where' =>
                        [
                            'ID' => $Call['CID']
                        ],
                    'Data' =>
                        [
                            'Expires' => microtime(true)+$Call['Run']['TTL'],
                            'Result' => $Call['Output']
                        ]
                ]);

        }

        return $Call;
    });