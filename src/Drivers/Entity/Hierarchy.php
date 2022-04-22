<?php

    setFn('Get Ancestors', function ($Call) {
        $ParentKey = $Call['Parent Key'];
        $Current = $Call['Data'];
        $Ancestors = [];
        do {
            if (empty($Current)) {
                break;
            }

            $ParentID = F::Dot($Current, $ParentKey);
            if (($ParentID == $Current['ID']) || ($ParentID === null)) {
                break;
            }

            $Ancestors[] = $ParentID;
            $Current = F::Run('Entity', 'Read', $Call, ['Where' => $ParentID, 'One' => true]);
        } while (true);

        $Ancestors = array_reverse($Ancestors);
        return $Ancestors;
    });

    setFn('Counters.Children', function ($Call) {
        $Children = 0;

        if (F::Dot($Call, 'Data.ID')) {
            $Children = F::Run(
                'Entity',
                'Count',
                [
                    'Entity' => $Call['Entity'],
                    'Where' =>
                        [
                            'Parent' => $Call['Data']['ID']
                        ]
                ]
            );
        }

        return $Children;
    });
