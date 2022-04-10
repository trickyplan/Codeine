<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Filter hooks
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeList', function ($Call) {
        $Call['Filter'] =
            [
                'Selected' => []
            ];

        if (isset($Call['Request']['Filter'])) {
            F::Log('Filter String is *detected*', LOG_INFO);

            foreach ($Call['Nodes'] as $Name => $Node) {
                if (F::Dot($Node, 'Filter.Enabled') && $Value = F::Dot($Call['Request']['Filter'], $Name)) {
                    if (empty($Value) || $Value == '!Any') {
                    } else {
                        $Call['Filter']['Selected'][$Name] = F::Run(
                            'Data.Type.' . $Node['Type'],
                            'Read',
                            [
                                'Name' => $Name,
                                'Node' => $Node,
                                'Value' => $Value
                            ]
                        );
                        F::Log('Filter by *' . $Name . '* is *enabled*', LOG_INFO);
                    }
                }
            }

            if (isset($Call['Filter']['Selected']) && !empty($Call['Filter']['Selected'])) {
                if (isset($Call['Where'])) {
                    $Call['Where'] = F::Merge($Call['Where'], $Call['Filter']['Selected']);
                } else {
                    $Call['Where'] = $Call['Filter']['Selected'];
                }
            }
        }

        return $Call;
    });

    setFn('afterList', function ($Call) {
        $Call['FID'] = 'Filter'; // ?

        foreach ($Call['Nodes'] as $Name => $Node) {
            if (F::Dot($Node, 'Filter.Enabled') && $Widget = F::Dot($Node, 'Filter.Widget')) {
                $Widget = F::Merge($Node, $Widget);

                $Widget['Entity'] = $Call['Entity'];
                $Widget['Node'] = $Name;
                $Widget['Name'] = 'Filter';
                $Widget['Class']['Node'] = strtolower(
                    'Widget_' . str_replace('.', '_', $Widget['Entity'] . '.' . $Name)
                );
                $Widget['Key'] = $Name;
                $Widget['ID'] = $Call['FID'] . '_' . strtr($Name, '.', '_');
                $Widget['Context'] = $Call['Context'];

                if (isset($Call['Where'])) {
                    if ($Set = F::Dot($Call['Where'], $Name)) {
                        $Widget['Value'] = $Set;
                    }
                }

                if (str_contains($Name, '.')) {
                    $Slices = explode('.', $Name);

                    foreach ($Slices as $Slice) {
                        $Widget['Name'] .= '[' . $Slice . ']';
                    }
                } else {
                    $Widget['Name'] .= '[' . $Name . ']';
                }

                if (isset($Node['Localized']) && $Node['Localized']) {
                    $Widget['Label'] = $Call['Entity'] . '.Entity:' . $Name . '.Label';
                } else {
                    $Widget['Label'] = $Call['Entity'] . '.Entity:' . $Name;
                }

                if (isset($Widget['Options'])) {
                    $Widget['Options'] = F::Live($Widget['Options']);
                    /*                    if (F::Dot($Widget, 'Any'))
                                        {
                                            if (isset($Node['Localized']) && $Node['Localized'])
                                                $Widget['Options']['!Any'] = '!Any';
                                            else
                                                $Widget['Options']['!Any'] = '∀';
                                        }*/
                }

                $Call['Output']['Filter'][] = $Widget;
            }
        }

        return $Call;
    });
