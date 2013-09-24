<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return $Call;
    });

    setFn('HashTest', function ($Call)
    {
        $Options = F::loadOptions('Security.Hash');

        foreach ($Options['Modes'] as $Mode => $Hash)
        {
            $ST = microtime(true);

            for($a = 0; $a < 1000; $a++) // FIXME Option
                F::Live($Hash, ['Value' => rand()]);

            $ST = (microtime(true)-$ST)/$a;

            $Data[] = [$Mode, $Hash['Service'], round(1/$ST).' hps'];
        }

        $Call['Output']['Content'][] =
              [
                  'Type' => 'Table',
                  'Value' => $Data
              ];

        return $Call;
    });

    setFn('EntropyTest', function ($Call)
    {
        $Options = F::loadOptions('Security.Entropy');

        foreach ($Options['Modes'] as $Mode => $Rand)
        {
            $Result = [];

            $ST = microtime(true);

            for($a = 0; $a < 4; $a++) // FIXME Option
                $Result[] = F::Live($Rand, ['Min' => 0, 'Max' => 1000]);

            $ST = (microtime(true)-$ST)/$a;

            $Data[] = [$Mode, $Rand['Service'], implode(', ',$Result), round(1/$ST).' hps'];
        }

        $Call['Output']['Content'][] =
              [
                  'Type' => 'Table',
                  'Value' => $Data
              ];

        return $Call;
    });

    setFn('UIDTest', function ($Call)
    {
        $Options = F::loadOptions('Security.UID');

        foreach ($Options['Modes'] as $Key => $Generator)
        {
            $UID = F::Live ($Generator);

            $Call['Output']['Content'][] =
              [
                  'Type' => 'Block',
                  'Class' => 'alert alert-success',
                  'Value' => $Key.' ('.$UID.')'
              ];
        }

        return $Call;
    });

    setFn('Rules', function ($Call)
    {
        $Rules = F::loadOptions('Security.Access.Rule')['Rules'];

        foreach ($Rules as $Rule['ID'] => $Rule)
            $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'Security/Access',
                    'ID' => '/Rule/Show/Short',
                    'Data' => $Rule
                ];

        return $Call;
    });

    setFn('Roles', function ($Call)
    {
        return F::Run('Security.Control.Role', 'Do', $Call);
    });