<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet
     * @package Codeine
     * @version 8.x
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            if (F::Dot($Call['Parsed'], 'Options.' . $IX . '.format') === null) {
                if (F::Dot($Call['Parsed'], 'Options.' . $IX . '.type') === null) {
                    $Format = $Call['Parslet']['Exec']['Type'];
                } else {
                    $Format = F::Dot($Call['Parsed'], 'Options.' . $IX . '.type');

                    F::Log(
                        'Please, replace "type" with "format" as more semantically correct',
                        LOG_WARNING,
                        ['Developer', 'Deprecated']
                    );
                    F::Log($Match, LOG_WARNING, ['Developer', 'Deprecated']);
                }
            } else {
                $Format = F::Dot($Call['Parsed'], 'Options.' . $IX . '.format');
            }

            $Match = F::Run('Formats.' . $Format, 'Read', ['Value' => trim($Call['Parsed']['Value'][$IX])]);

            foreach ($Call['Inherited'] as $Key) {
                if (isset($Call[$Key])) {
                    $Match['Call'][$Key] = $Call[$Key];
                }
            }

            $Output = F::Live($Match);

            // FIXME Add Return Key
            if (is_array($Output)) {
                $Output = '{}';
            }

            if (is_float($Output)) {
                $Output = str_replace(',', '.', $Output);
            }

            $Call['Replace'][$Call['Parsed']['Match'][$IX]] = $Output;
        }

        return $Call;
    });
