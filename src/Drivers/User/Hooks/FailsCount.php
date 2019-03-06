<?php

    setFn('Increase', function ($Call) {
        $Action = F::Dot($Call, 'Fails.Action');
        $ID = F::Live(F::Dot($Call, 'Fails.ID'), $Call);

        $Fails = F::Run('IO', 'Read', [
            'Storage' => 'FailsCount',
            'Where' => [
                'ID' => $ID
            ],
            'IO One' => true
        ]) ?? [
            $Action.' Fails' => 0,
        ];

        $Fails[$Action.' Fails']++;
        $Fails[$Action.' Last Fail'] = time();

        F::Run('IO', 'Write', [
            'Storage' => 'FailsCount',
            'Where' => [
                'ID' => $ID
            ],
            'Data' => $Fails
        ]);

        return $Call;
    });

    setFn('React', function ($Call) {
        $Action = F::Dot($Call, "Fails.Action");
        if (!empty($Action)) 
        {
            $LongIP = ip2long($Call['HTTP']['IP']);
            $Fails = F::Run('IO', 'Read', ['Storage' => 'FailsCount', 'Where' => $LongIP, 'IO One' => true]);
            $FailsCount = intval($Fails[$Action . ' Fails']);
            $MaxFailsCount = intval(F::Dot($Call, 'Fails.Max'));
            $Condition = F::Live(F::Dot($Call, 'Fails.Actions.'.$Action.'.Condition'), ['A' => $MaxFailsCount, 'B' => $FailsCount]);
            if ($Condition)
                $Call = F::Live(F::Dot($Call, 'Fails.Actions.'.$Action.'.Reaction'), $Call);
        }

        return $Call;
    });

    setFn('Reset', function ($Call) {
        
        return $Call;
    });