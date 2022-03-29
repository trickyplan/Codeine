<?php

    setFn('Generate.Key', function ($Call) {
        $V3Ext =
            'authorityKeyIdentifier=' . implode(',', $Call['Certificate']['Key']['Authority Key Identifier'])
            . PHP_EOL . 'basicConstraints=' . $Call['Certificate']['Key']['Basic Constraints']
            . PHP_EOL . 'keyUsage=' . implode(', ', $Call['Certificate']['Key']['Usage'])
            . PHP_EOL . 'subjectAltName=@alt_names'
            . PHP_EOL . '[alt_names]';

        if (is_array($Call['Certificate']['Domain'])) {
            ;
        } else {
            $Call['Certificate']['Domain'] = (array)$Call['Certificate']['Domain'];
        }

        foreach ($Call['Certificate']['Domain'] as $IX => $Domain) {
            $V3Ext .= PHP_EOL . 'DNS.' . ($IX + 1) . '=' . $Domain;
        }

        file_put_contents('v3.ext', $V3Ext);

        F::Run(
            'Code.Run.External.Exec',
            'Run',
            [
                'Command' => F::Live(
                    'openssl genrsa -out $Filename $KeySize',
                    [
                        'Filename' => $Call['Certificate']['Filename'] . '.key',
                        'KeySize' => $Call['Certificate']['Key']['Size']
                    ]
                )
            ]
        );

        return $Call;
    });

    setFn('Generate.Request', function ($Call) {
        $Subject = [];
        foreach ($Call['Certificate']['Subject'] as $Key => $Value) {
            $Subject[] = $Key . '=' . $Value;
        }

        F::Run('Code.Run.External.Exec', 'Run', [
            'Command' => F::Live('openssl req -new -key $KeyFilename -out $CSRFilename -subj "/$Subject"', [
                'KeyFilename' => $Call['Certificate']['Filename'] . '.key',
                'CSRFilename' => $Call['Certificate']['Filename'] . '.csr',
                'Subject' => implode('/', $Subject),
            ])
        ]);

        return $Call;
    });

    setFn('Generate.Certificate', function ($Call) {
        F::Run(
            'Code.Run.External.Exec',
            'Run',
            [
                'Command' => F::Live(
                    'openssl x509 -req -in $CSRFilename -CA $CACertificate -CAkey $CAKey \
                -CAcreateserial -CAserial $SeqFilename -extfile v3.ext -out $PemFilename -days $ValidityPeriod',
                    [
                        'CSRFilename' => $Call['Certificate']['Filename'] . '.csr',
                        'CACertificate' => $Call['Certificate']['Authority']['Certificate'],
                        'CAKey' => $Call['Certificate']['Authority']['Key'],
                        'SeqFilename' => $Call['Certificate']['Filename'] . '.seq',
                        'PemFilename' => $Call['Certificate']['Filename'] . '.pem',
                        'ValidityPeriod' => $Call['Certificate']['Validity Period']
                    ]
                )
            ]
        );
        return $Call;
    });

    setFn('Verify.Certificate.Expires', function ($Call) {
        $Expires = null;
        $Result = F::Run(
            'Code.Run.External.Exec',
            'Run',
            [
                'Command' => 'openssl x509 -enddate -noout -in ' . F::Dot($Call, 'Certificate.Filename')
            ]
        );

        if ($Result['Code'] == 0) {
            $Date = explode('=', $Result['Result']);
            $Expires = new DateTime($Date[1]);
        }

        return $Expires;
    });