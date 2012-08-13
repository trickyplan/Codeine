<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Do', function ($Call)
    {
        return $Call;
    });

    self::setFn('HashTest', function ($Call)
    {
        $Options = F::loadOptions('Security.Hash');

        foreach ($Options['Algos'] as $Key=> $Hash)
        {
            $ST = microtime(true);

            for($a = 0; $a < 1000; $a++) // FIXME Option
                F::Run('Security.Hash.'.$Hash, 'Get', array('Value' => rand()));

            $ST = (microtime(true)-$ST)/$a;

            $Call['Output']['Content'][] =
              array(
                  'Type' => 'Block',
                  'Class' => 'alert alert-success',
                  'Value' => $Key.' ('.$Hash.') '.round(1/$ST).' hash/sec'
              );
        }

        return $Call;
    });

    self::setFn('Rules', function ($Call)
    {
        $Rules = F::loadOptions('Security.Access.Rule')['Rules'];

        foreach ($Rules as $Rule['ID'] => $Rule)
            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Template',
                    'Scope' => 'Security/Access',
                    'ID' => '/Rule/Show/Short',
                    'Data' => $Rule
                );

        return $Call;
    });