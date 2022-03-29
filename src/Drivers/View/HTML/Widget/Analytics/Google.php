<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Make', function ($Call) {
        $Call = F::Hook('Analytics.Google.Make.Before', $Call);

        if (F::Dot($Call, 'Analytics.Google.Enabled')) {
            $Call['ID'] = isset($Call['ID']) ? $Call['ID'] : F::Dot($Call, 'Analytics.Google.ID');
            $Code = '';

            if (F::Dot($Call, 'Analytics.Google.DoNotTrack') && F::Run('System.Interface.HTTP.DNT', 'Detect', $Call)) {
                $Message = 'Google.Analytics *' . $Call['ID'] . '* Suppressed by *Do Not Track*';
                $Code = '<!-- ' . $Message . ' -->';
                F::Log($Message, LOG_INFO, 'Marketing');
            } else {
                if (F::Dot($Call, 'Analytics.Google.URLs.Disabled') !== null && in_array(
                        $Call['HTTP']['URL'],
                        F::Dot($Call, 'Analytics.Google.URLs.Disabled')
                    )) {
                    $Message = 'Google.Analytics *' . $Call['ID'] . '* Suppressed by *URLs*';
                    $Code = '<!-- ' . $Message . ' -->';
                    F::Log($Message, LOG_INFO, 'Marketing');
                } else {
                    if (F::Dot($Call, 'Analytics.Google.Environment.' . F::Environment()) === true) {
                        $Code = F::Live(
                            F::Run(
                                'View',
                                'Load',
                                $Call,
                                [
                                    'Scope' => 'View.HTML.Widget.Analytics',
                                    'ID' => 'Google'
                                ]
                            ),
                            $Call
                        );

                        $Message = 'Google.Analytics *' . $Call['ID'] . '* Registered';
                        F::Log($Message, LOG_INFO, 'Marketing');
                    } else {
                        $Message = 'Google.Analytics *' . $Call['ID'] . '* Suppressed by *Environment*';
                        $Code = '<!-- ' . $Message . ' -->';
                        F::Log($Message, LOG_INFO, 'Marketing');
                    }
                }
            }
        } else {
            $Message = 'Google.Analytics *' . $Call['ID'] . '* Suppressed by *Analytics.Google.Enabled option*';
            $Code = '<!-- ' . $Message . ' -->';
            F::Log($Message, LOG_INFO, 'Marketing');
        }

        $Call = F::Hook('Analytics.Google.Make.After', $Call);
        return $Code;
    });