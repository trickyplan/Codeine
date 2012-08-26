<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
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

    self::setFn('UIDTest', function ($Call)
    {
        $Options = F::loadOptions('Security.UID');

        foreach ($Options['Modes'] as $Key => $Generator)
        {
            $UID = F::Live ($Generator);

            $Call['Output']['Content'][] =
              array(
                  'Type' => 'Block',
                  'Class' => 'alert alert-success',
                  'Value' => $Key.' ('.$UID.')'
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

    self::setFn('Roles', function ($Call)
    {
        return F::Run('Security.Control.Role', 'Do', $Call);
    });