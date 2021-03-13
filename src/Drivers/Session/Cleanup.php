<?php

    setFn('Do', function ($Call)
    {
        if (F::Dot($Call, 'Session.Cleanup.Expired.Enabled'))
            $Call = F::Apply(null, 'Cleanup.Expired', $Call);

        if (F::Dot($Call, 'Session.Cleanup.Inactive.Enabled'))
            $Call = F::Apply(null, 'Cleanup.Inactive', $Call);

        return $Call;
    });

    setFn('Cleanup.Expired', function ($Call)
    {
        F::Run('Entity', 'Delete', $Call,
            [
                'Entity' => $Call['Bundle'],
                'Where'  =>
                    [
                        'Expire' =>
                        [
                            '$lt' => time()
                        ]
                    ]
            ]);
        return $Call;
    });

    setFn('Cleanup.Inactive', function ($Call)
    {
        F::Apply('Entity', 'Delete', $Call,
            [
                'Entity' => $Call['Bundle'],
                'Where'  =>
                    [
                        'Modified' =>
                        [
                            '$lt' => time() - F::Dot($Call, 'Session.Cleanup.Inactive.TTL')
                        ]
                    ]
            ]);
        return $Call;
    });