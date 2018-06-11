<?php

    setFn('RTB.SSP.Winner.Exists', function ($Call)
    {
        if (F::Dot($Call, 'RTB.DryRun'))
            ;
        else
            F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Time' => uniqid(true),
                'Where' =>
                [
                    'ID' => F::Dot($Call, 'RTB.Winner.Bid.nurl')
                ]
            ]);
        
        return $Call;
    });