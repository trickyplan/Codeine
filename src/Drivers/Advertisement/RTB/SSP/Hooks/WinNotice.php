<?php

    setFn('Do', function ($Call)
    {
        return F::Dot($Call, 'RTB.SSP.Client.WinNotice.Result', F::Run('IO', 'Read', [
            'Storage' => 'Web',
            'Time' => uniqid(true),
            'Where' => [
                'ID' => array_reduce(F::Dot($Call, 'RTB.Result.Seats'), function ($URLs, $WinnerBid) {
                    if (!empty($WinnerBid['nurl'])) {
                        $URLs[] = $WinnerBid['nurl'];
                    }
                    return $URLs;
                }, [])
            ]
        ]));
    });