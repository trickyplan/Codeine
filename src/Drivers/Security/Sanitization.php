<?php

setFn('Do', function($Call) {
    $Nodes = F::Dot($Call, 'Nodes');
    foreach ($Nodes as $Name => $Node) {
        if (isset($Node['Filter'])) {
            $Call = F::Run('Security.Filter', 'Do', $Call, [
                'Filter' => [
                    'Type' => $Node['Filter'],
                    'Name' => $Name
                ]
            ]);
        }
    }

    return $Call;
});