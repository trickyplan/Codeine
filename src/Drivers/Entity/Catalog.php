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

        $Call['Layouts'][] = ['Scope' => $Call['Entity'], 'ID' => 'Catalog', 'Context' => $Call['Context']];
        $Call['Fields'] = [$Call['Key']];

        $Call = F::Hook('beforeCatalog', $Call);

            $Elements = F::Run('Entity', 'Read',
                [
                    'Entity' => $Call['Key'],
                    'Where'  => []
                ]);

            $Values = [];

            if (count($Elements) > 0)
            {
                foreach ($Elements as $Element)
                {
                    $Value = F::Run('Entity', 'Count',
                    [
                        'Entity' => $Call['Entity'],
                        'RTTL'   => 600,
                        'Where' =>
                        [
                            $Call['Key'] => $Element['ID']
                        ]
                    ]);

                    if ($Value > 0)
                        $Values[$Element['ID']] = $Value;
                }

                arsort($Values);

                $Call['Output']['Content'][] =
                    [
                        'Type'    => 'TagCloud',
                        'Value'   => $Values,
                        'Context' => $Call['Context'],
                        'Minimal' => $Call['Minimal'],
                        'Entity'  => $Call['Entity'],
                        'Key'     => $Call['Key']
                    ];
            }

        $Call = F::Hook('afterCatalog', $Call);

        return $Call;
    });