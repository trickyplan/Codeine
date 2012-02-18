<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn('Create', function ($Call)
    {
        $Model = F::Run('Engine.Entity', 'Model', $Call);

        // TODO Heterogenic mapping

        foreach ($Model['Nodes'] as $Name => $Node)
        {
            if (F::isCall($Node))
                $Created[$Name] = F::Run($Node['Service'], $Node['Method'], $Call['Data'][$Name], $Node['Call']);
            else
            {
                if (isset($Call['Data'][$Name]))
                    $Created[$Name] = $Call['Data'][$Name];
                else
                {
                    if(isset($Node['Default']))
                        $Created[$Name] = $Node['Default'];
                }
            }
        }

        $Call['Data'] = array();

        $ID = F::Run('Engine.IO', 'Write', $Call,
            array (
                  'Storage' => $Model['Storage'],
                  'Scope' => $Call['Entity'],
                  'Data' => $Created
            ));

        return $Created;
    });

    self::setFn('Read', function ($Call)
    {
        $Model = F::Run('Engine.Entity', 'Model', $Call);

        return F::Run('Engine.IO', 'Read', $Call,
            array (
                  'Storage' => $Model['Storage'],
                  'Scope'   => $Call['Entity']
            ));
    });

    self::setFn('Update', function ($Call)
    {
        $Model = F::Run('Engine.Entity', 'Model', $Call);

        $Updated = array();

        foreach ($Model['Nodes'] as $Name => $Node)
        {
            if (F::isCall($Node))
                $Updated[$Name] = F::Run($Node['Service'], $Node['Method'], $Call['Data'][$Name], $Node['Call']);
            else
            {
                if (isset($Call['Data'][$Name]))
                    $Updated[$Name] = $Call['Data'][$Name];
            }
        }

        $Call['Data'] = array();
        d(__FILE__, __LINE__, $Updated);

        F::Run('Engine.IO', 'Write', $Call,
            array (
                  'Storage' => $Model['Storage'],
                  'Scope'   => $Call['Entity'],
                  'Data'    => $Updated,
            ));

        return $Updated;
    });

    self::setFn('Delete', function ($Call)
    {
        $Model = F::Run('Engine.Entity', 'Model', $Call);

        F::Run('Engine.IO', 'Write', $Call,
            array (
                  'Storage' => $Model['Storage'],
                  'Scope'   => $Call['Entity'],
                  'Data'    => null
            ));

        return $Call;
    });

    self::setFn('Model', function ($Call)
    {
        // TODO Realize Model
        return F::loadOptions('Entity.'.$Call['Entity']);
    });