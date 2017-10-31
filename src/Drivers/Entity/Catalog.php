<?php

    /* Codeine
     * @author bergstein@trickyplan.com
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

            $Values = F::Run('Entity', 'Distinct', $Call,
                [
                    'Entity'    => $Call['Entity'],
                    'Fields'    => [$Call['Key']],
                    'No Where'  => true
                ]);
         
            if (count($Values) > 0)
            {
                $Values = $Values[$Call['Key']];

                foreach ($Values as $Value)
                {
                    $Categories[$Value] = F::Run('Entity', 'Count',
                    [
                        'Entity' => $Call['Entity'],
                        'Where' =>
                        [
                            $Call['Key'] => $Value
                        ]
                    ]);

                    if ($Categories[$Value] == 0)
                        unset($Categories[$Value]);
                }

                arsort($Categories);

                $Call['Output']['Content'][] =
                    [
                        'Type'    => 'TagCloud',
                        'Value'   => $Categories,
                        'Context' => $Call['Context'],
                        'Minimal' => $Call['Minimal'],
                        'Entity'  => $Call['Entity'],
                        'Key'     => $Call['Key']
                    ];
            }

        $Call = F::Hook('afterCatalog', $Call);

        return $Call;
    });