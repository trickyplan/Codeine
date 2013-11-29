<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    setFn('Do', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read',
                [
                    'Entity' => 'Notify',
                    'Where' =>
                    [
                        'User' =>
                        (isset($Call['Session']['User']['ID'])?
                            $Call['Session']['User']['ID']: $Call['SID']),
                        'Readed' => 0
                    ]
                ]);

        if (!empty($Elements))
        {
            foreach($Elements as $Element)
            {
                F::Run('Entity', 'Update', $Call,
                    [
                        'Entity'    => 'Notify',
                        'Where'     => $Element['ID'],
                        'Data'      => ['Readed' => time()]
                    ]);
            }

            $Call['Output']['Content'] = $Elements;
        }
        else
            $Call['Output']['Content'] = [];

        return $Call;
    });