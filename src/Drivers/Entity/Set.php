<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        $Call = F::Hook('beforeSetDo', $Call);

        $Call = F::Run('Entity', 'Load', $Call);

        if (F::Dot($Call, 'Request.Data')) {
            $Sets = F::Dot($Call, 'Request.Data');
        } else {
            $Sets = [$Call['Key'] => $Call['Value']];
        }

        $Data = F::Run(
            'Entity',
            'Read',
            $Call,
            [
                'One' => true
            ]
        );

        foreach ($Sets as $Key => $Value) {
            if (F::Dot($Call['Nodes'][$Key], 'Allow.Set') === true
                or F::Dot($Call['Nodes'][$Key], 'Widgets.Write')
                or F::Dot($Call['Nodes'][$Key], 'Widgets.Update')) // FIXME
            {
                $Data = F::Dot($Data, $Key, $Value);
                $Call['Output']['Content'][] = $Key . ' = ' . $Value;
            }
        }

        F::Run(
            'Entity',
            'Update',
            $Call,
            [
                'Data' => $Data
            ]
        );

        $Call['Output']['Content'][] = 'OK';

        $Call = F::Hook('afterSetDo', $Call);

        return $Call;
    });

    setFn('All', function ($Call) {
        $Entities = F::Run('Entity', 'Read', $Call);

        $Call = F::Apply('Code.Progress', 'Start', $Call);

        $Call['Progress']['Max'] = count($Entities);

        foreach ($Entities as $Entity) {
            $Call['Progress']['Now']++;
            F::Run(
                'Entity',
                'Update',
                [
                    'Entity' => $Call['Entity'],
                    'Where' => $Entity['ID'],
                    'Live Fields' => [$Call['Field']]
                ]
            );
            $Call = F::Apply('Code.Progress', 'Log', $Call);
        }

        $Call = F::Apply('Code.Progress', 'Finish', $Call);

        return $Call;
    });