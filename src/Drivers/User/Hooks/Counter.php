<?php

    setFn('Increase', function ($Call) {
        $Action = F::Dot($Call, 'Counter.Action');
        $ID = F::Live(F::Dot($Call, 'Counter.ID'), $Call);

        $Actions = F::Run('IO', 'Read', [
            'Storage' => 'ActionsCounter',
            'Where' => [
                'ID' => $ID
            ],
            'IO One' => true
        ]) ?? [
            $Action.' Count' => 0,
        ];

        $Actions[$Action.' Count']++;
        $Actions[$Action.' Last Fail'] = time();

        F::Run('IO', 'Write', [
            'Storage' => 'ActionsCounter',
            'Where' => [
                'ID' => $ID
            ],
            'Data' => $Actions
        ]);

        return $Call;
    });

    setFn('React', function ($Call) {
        $Action = F::Dot($Call, "Counter.Action");
        if (!empty($Action)) 
        {
            $ID = F::Live(F::Dot($Call, 'Counter.ID'), $Call);
            $Actions = F::Run('IO', 'Read', ['Storage' => 'ActionsCounter', 'Where' => $ID, 'IO One' => true]);
            $ActionsCount = intval($Actions[$Action . ' Count']);
            $MaxActionsCount = intval(F::Dot($Call, 'Counter.Max'));
            $Condition = F::Live(F::Dot($Call, 'Counter.Actions.'.$Action.'.Condition'), ['A' => $MaxActionsCount, 'B' => $ActionsCount]);
            if ($Condition)
                $Call = F::Live(F::Dot($Call, 'Counter.Actions.'.$Action.'.Reaction'), $Call);
        }

        return $Call;
    });

    setFn('Reset', function ($Call) {
        
        return $Call;
    });