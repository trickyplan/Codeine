<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Create', function ($Call)
    {
        $Model = F::Run('Entity', 'Model', $Call);

        // TODO Heterogenic mapping
        $Created = array();

        foreach ($Model['Nodes'] as $Name => $Node)
        {
            if (F::isCall($Node))
                $Created[$Name] = F::Run($Node['Service'], $Node['Method'],
                    $Node['Call'],
                    array(
                         'Data' => F::Merge($Call['Data'], $Created),
                         'Node' => $Name));
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

        $Call['RAW'] = $Call['Data']; // FIXME
        $Call['Data'] = array(); // FIXME

        $Created['ID'] = F::Run('IO', 'Write', $Call,
            array (
                  'Storage' => $Model['Storage'],
                  'Scope' => $Call['Entity'],
                  'Data' => $Created
            ));
        // FIXME
        F::Run('Code.Flow.Hook', 'Run', $Call, $Model, array ('Data' => $Created, 'On' => 'afterCreate'));

        return $Created;
    });

    self::setFn('Read', function ($Call)
    {
        $Model = F::Run('Entity', 'Model', $Call);

        return F::Run('IO', 'Read', $Call,
            array (
                  'Storage' => $Model['Storage'],
                  'Scope'   => $Call['Entity']
            ));
    });

    self::setFn('Update', function ($Call)
    {
        $Model = F::Run('Entity', 'Model', $Call);

        $Updated = array();

        foreach ($Model['Nodes'] as $Name => $Node)
        {
            if (F::isCall($Node))
                {
                    if ($Response =  F::Run($Node['Service'], $Node['Method'], $Node['Call'], array ('Data' => $Call['Data'],
                                                                                                    'Node'  => $Name)) !== null)
                        $Updated[$Name] = $Response;
                }
            else
                {
                    if (isset($Call['Data'][$Name]))
                        $Updated[$Name] = $Call['Data'][$Name];
                }
        }

        $Call['RAW'] = $Call['Data']; // FIXME
        $Call['Data'] = array();


        F::Run('IO', 'Write', $Call,
            array (
                  'Storage' => $Model['Storage'],
                  'Scope'   => $Call['Entity'],
                  'Data'    => $Updated,
            ));

        F::Run('Code.Flow.Hook', 'Run', $Call, $Model, array ('Data' => $Updated,
                                                             'On'    => 'afterUpdate'));
        return $Updated;
    });

    self::setFn('Delete', function ($Call)
    {
        $Model = F::Run('Entity', 'Model', $Call);

        F::Run('IO', 'Write', $Call,
            array (
                  'Storage' => $Model['Storage'],
                  'Scope'   => $Call['Entity'],
                  'Data'    => null
            ));

        return $Call;
    });

    self::setFn('Model', function ($Call)
    {
        $Call['Model'] = F::loadOptions('Entity.'.$Call['Entity']);

        if (isset($Call['Processors']))
        {
            foreach ($Call['Processors'] as $Processor)
                $Call['Model'] = F::Live($Processor, $Call);
        }

        return $Call['Model'];
    });