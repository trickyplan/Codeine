<?php

    setFn('ByEmail', function ($Call) {
        $Email = F::Live(F::Dot($Call, 'Email'), $Call);
        $Period = intval(F::Dot($Call, 'Period'));
        $Action = F::Dot($Call, 'Action');
        $Actions = F::Run('IO', 'Read', [
            'Storage' => 'ActionsCounter',
            'Where' => [
                'ID' => $Email
            ],
            'IO One' => true
        ]);
        $LastFail = intval(F::Dot($Actions, $Action.' Last'));

        if ($Period > (time() - $LastFail)) {
            $Call['Errors']['User'][] = 'Blocked';
            $Call = F::Apply('Error.Page', 'Do', $Call, [
                'Code' => '403/Blocked',
            ]);
        } else {
            F::Run('User.Hooks.Counter', 'Reset', $Call, [
                'Counter' => 
                [
                    'ID' => '$Request.EMail'
                ]
            ]);
        }

        return $Call;
    });