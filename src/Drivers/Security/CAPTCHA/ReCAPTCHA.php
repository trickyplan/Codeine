<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    /* Look at ./src/Options/Security/CAPTCHA/ReCAPTCHA.json for keys*/

    setFn('Prepare', function ($Call) {
        if (F::Dot($Call, 'ReCAPTCHA.Enabled')) {
            $Call['ReCAPTCHA']['Place'] = isset($Call['ReCAPTCHA']['Place']) ? $Call['ReCAPTCHA']['Place'] : 'ReCAPTCHA';
            $Call['Output'][$Call['ReCAPTCHA']['Place']][] =
                '<script src="https://www.google.com/recaptcha/api.js?hl=' . ($Call['Locale'] ?? 'en') . '" async defer></script>' .
                '<div class="g-recaptcha"
                data-sitekey="' . $Call['ReCAPTCHA']['Public'] . '"
                data-size="compact"' .
                '></div>';
        }
        return $Call;
    });

    setFn('Check', function ($Call) {
        if (F::Dot($Call, 'ReCAPTCHA.Enabled')) {
            if (isset($Call['Request']['g-recaptcha-response'])) {
                $Result = F::Run(
                    'IO',
                    'Write',
                    [
                        'Storage' => 'Web',
                        'Where' => $Call['ReCAPTCHA']['Endpoint'],
                        'Output Format' => 'Formats.JSON',
                        'IO One' => true,
                        'Data' =>
                            [
                                'secret' => $Call['ReCAPTCHA']['Private'],
                                'response' => $Call['Request']['g-recaptcha-response'],
                                'remoteip' => $Call['HTTP']['IP']
                            ]
                    ]
                );
            } else {
                $Result = ['success' => false, 'score' => 0];
            }

            if (isset($Result['success']) && $Result['success']) {
                ;
            } else {
                F::Log('ReCAPTCHA Response: ' . j($Result), LOG_WARNING, ['Security', 'CAPTCHA', 'ReCAPTCHA']);
                $Call = F::Hook('CAPTCHA.Failed', $Call);
                $Call['Errors']['CAPTCHA'][] =
                    [
                        'Validator' => 'ReCAPTCHA',
                        'Entity' => isset($Call['Entity']) ? $Call['Entity'] : '',
                        'Name' => 'ReCAPTCHA',
                        'ID' => null
                    ];
            }
        } else {
            $Result = ['success' => true, 'score' => 1];
        }

        return $Call;
    });