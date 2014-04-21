<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Merge(F::loadOptions('Entity.'.$Call['Entity']), $Call); // FIXME

        $Call['Layouts'][] = ['Scope' => $Call['Entity'], 'ID' => 'Catalog'];
        $Call['Fields'] = [$Call['Key']];
        $Call['Distinct'] = true;

        $Call = F::Hook('beforeCatalog', $Call);

            $Elements = F::Run('Entity', 'Read', $Call);

            $Values = [];

            if (count($Elements) > 0)
            {
                foreach ($Elements as $Element)
                    $Values[$Element[$Call['Key']]] = F::Run('Entity', 'Count', $Call,
                        [
                            'Entity' => $Call['Entity'],
                            'RTTL'   => $Call['Entity RTTL'],
                            'Where' =>
                            [
                                $Call['Key'] => $Element[$Call['Key']]
                            ]
                        ]);

                arsort($Values);

                $Call['Output']['Content'][] =
                    [
                        'Type'    => 'TagCloud',
                        'Value'   => $Values,
                        'Minimal' => $Call['Minimal'],
                        'Entity'  => $Call['Entity'],
                        'Key'     => $Call['Key']
                    ];
            }

        $Call = F::Hook('afterCatalog', $Call);

        return $Call;
    });