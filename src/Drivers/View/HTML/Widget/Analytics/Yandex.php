<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Make', function ($Call) {
        $Call = F::Hook('Analytics.Yandex.Make.Before', $Call);


        $Call['ID'] = isset($Call['ID']) ? $Call['ID'] : F::Dot($Call, 'Analytics.Yandex.ID');

        if (F::Dot($Call, 'Analytics.Yandex.Enabled')) {
            $Code = '';

            if (F::Dot($Call, 'Analytics.Yandex.DoNotTrack') && F::Run('System.Interface.HTTP.DNT', 'Detect', $Call)) {
                $Message = 'Yandex.Metrika *' . $Call['ID'] . '* Suppressed by *Do Not Track*';
                $Code = '<!-- ' . $Message . ' -->';
                F::Log($Message, LOG_INFO, 'Marketing');
            } else {
                if (F::Dot($Call, 'Analytics.Yandex.URLs.Disabled') !== null && in_array(
                        $Call['HTTP']['URL'],
                        F::Dot($Call, 'Analytics.Yandex.URLs.Disabled')
                    )) {
                    $Message = 'Yandex.Metrika *' . $Call['ID'] . '* Suppressed by *URLs*';
                    $Code = '<!-- ' . $Message . ' -->';
                    F::Log($Message, LOG_INFO, 'Marketing');
                } else {
                    if (F::Dot($Call, 'Analytics.Yandex.Environment.' . F::Environment()) === true) {
                        $Code = F::Live(
                            F::Run(
                                'View',
                                'Load',
                                $Call,
                                [
                                    'Scope' => 'View.HTML.Widget.Analytics',
                                    'ID' => 'Yandex'
                                ]
                            ),
                            $Call
                        );

                        $Message = 'Yandex.Metrika *' . $Call['ID'] . '* Registered';
                        F::Log($Message, LOG_INFO, 'Marketing');
                    } else {
                        $Message = 'Yandex.Metrika *' . $Call['ID'] . '* Suppressed by *Environment*';
                        $Code = '<!-- ' . $Message . ' -->';
                        F::Log($Message, LOG_INFO, 'Marketing');
                    }
                }
            }
        } else {
            $Message = 'Yandex.Metrika *' . $Call['ID'] . '* Suppressed by *Analytics.Yandex.Enabled option*';
            $Code = '<!-- ' . $Message . ' -->';
            F::Log($Message, LOG_INFO, 'Marketing');
        }

        $Call = F::Hook('Analytics.Yandex.Make.After', $Call);

        return $Code;
    });