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
                            $Call['Session']['User']['ID']: $Call['SID'])
                    ]
                ]);

        if (!empty($Elements))
        {
            foreach($Elements as $Element)
                F::Run('Entity', 'Delete',
                    [
                        'One'       => true,
                        'Entity'    => 'Notify',
                        'Where'     => $Element['ID']
                    ]);

            $Call['Output']['Content'] = $Elements;
        }
        else
            $Call['Output']['Content'] = [];

        return $Call;
    });