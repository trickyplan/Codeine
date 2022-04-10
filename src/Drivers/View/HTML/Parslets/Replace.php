<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Data) {
            if (isset($Call['Parsed']['Options'][$IX]['scope'])) {
                $Call['Scope'] = $Call['Parsed']['Options'][$IX]['scope'];

                if (isset($Call['Parsed']['Options'][$IX]['id'])) {
                    $Call['ID'] = $Call['Parsed']['Options'][$IX]['id'];
                    $IDs = [$Call['ID']];

                    if (isset($Call['Context']) && !empty($Call['Context'])) {
                        $IDs[] = $Call['ID'] . '.' . $Call['Context'];
                    }

                    $IDs = array_reverse($IDs);

                    if (isset($Call['Scope'])) {
                        $Call['Scope'] = strtr($Call['Scope'], '.', '/');
                    }

                    $Data = jd($Data);

                    $Template = F::Run(
                        'IO',
                        'Read',
                        $Call,
                        [
                            'Storage' => 'Layout',
                            'Where' =>
                                [
                                    'ID' => $IDs
                                ],
                            'IO One' => true
                        ]
                    );

                    $Call['Replace'][$Call['Parsed']['Match'][$IX]] = preg_replace_callback(
                        '@<codeine-replace-variable>(.*?)</codeine-replace-variable>@Ssu',
                        function ($Matches) use ($Data) {
                            return F::Dot($Data, $Matches[1]);
                        },
                        $Template
                    );
                } else {
                    F::Log('ID attribute is required for template parslet', LOG_ERR);
                }
            } else {
                F::Log('Scope attribute is required for template parslet', LOG_ERR);
            }
        }

        return $Call;
    });
