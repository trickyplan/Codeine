<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.4
     */

    setFn('Before', function ($Call) {
        $Call = F::Hook('beforeDeleteBefore', $Call);

        if (isset($Call['Where'])) {
            $Call['Where'] = F::Live($Call['Where']);
        }

        $Call['Data'] = F::Run('Entity', 'Read', $Call, ['One' => true, 'Limit' => ['From' => 0, 'To' => 1]]);

        $Call = F::Hook('afterDeleteBefore', $Call);
        return $Call;
    });

    setFn('Do', function ($Call) {
        $Call = F::Hook('beforeDeleteDo', $Call);

        $Call = F::Apply(null, $Call['HTTP']['Method'], $Call);

        $Call = F::Hook('afterDeleteDo', $Call);

        return $Call;
    });

    setFn('GET', function ($Call) {
        $Call = F::Hook('beforeDeleteGet', $Call);

        $Call['Scope'] = isset($Call['Scope']) ? $Call['Entity'] . '/' . $Call['Scope'] : $Call['Entity'];

        $Call['Delete']['Count'] = F::Run('Entity', 'Count', $Call);

        if (empty($Call['Data'])) {
            $Call = F::Hook('onDeleteNotFound', $Call);
        } else {
            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Scope'],
                    'ID' => isset($Call['Custom Layouts']['Delete']) ?
                        $Call['Custom Layouts']['Delete'] : 'Delete',
                    'Context' => $Call['Context'],
                    'Data' => $Call['Data']
                ];

            if ($Call['Delete']['Count'] > 1) {
                $Call['Output']['Content'][] =
                    [
                        'Type' => 'Block',
                        'Class' => 'alert alert-danger',
                        'Value' => '<codeine-locale>' . $Call['Entity'] . '.Delete:WillBeDeleted</codeine-locale>: ' . $Call['Delete']['Count']
                    ];
            } else {
                $Call['Output']['Content'][] =
                    [
                        'Type' => 'Template',
                        'Scope' => $Call['Scope'] . '/Show',
                        'ID' => 'Delete',
                        'Data' => $Call['Data']
                    ];
            }

            $Call = F::Hook('afterDelete', $Call);
        }

        $Call = F::Hook('afterDeleteGet', $Call);

        return $Call;
    });


    setFn('POST', function ($Call) {
        $Call = F::Hook('beforeDeletePost', $Call);

        $Call['Data'] = F::Apply('Entity', 'Delete', $Call);

        $Call = F::Hook('afterDeletePost', $Call);
        return $Call;
    });
