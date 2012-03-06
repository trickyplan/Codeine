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

        foreach ($Model['Nodes'] as $Name => $Node)
        {
            foreach ($Call['Processors']['Nodes'] as $Processor)
            {
                $Call['Data'][$Name] = F::Live($Processor, array('Method' => 'Write', 'Data' => $Call['Data'], 'Name' => $Name, 'Node' => $Node));
            }
        }

        $Call['Data']['ID'] = F::Run('IO', 'Write', $Call,
            array (
                  'Storage' => $Model['Storage'],
                  'Scope' => $Call['Entity']
            ));

        // FIXME
        F::Run('Code.Flow.Hook', 'Run', $Call, $Model, array ('On' => 'afterCreate'));

        return $Call['Data'];
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

        foreach ($Model['Nodes'] as $Name => $Node)
        {
            if (F::isCall($Node))
                {
                    if ($Response =  F::Live($Node, array ('Data' => $Call['Data'], 'Node'  => $Name)) !== null)
                        $Call['Data'][$Name] = $Response;
                }
            else
                {
                    if (isset($Call['Data'][$Name]))
                        $Call['Data'][$Name] = $Call['Data'][$Name];
                }
        }

        d(__FILE__, __LINE__, $Call);
        F::Run('IO', 'Write', $Call,
            array (
                  'Storage' => $Model['Storage'],
                  'Scope'   => $Call['Entity']
            ));

        F::Run('Code.Flow.Hook', 'Run', $Call, $Model, array ('On'    => 'afterUpdate'));

        return $Call['Data'];
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

        if (isset($Call['Processors']['Model']))
        {
            foreach ($Call['Processors']['Model'] as $Processor)
                $Call['Model'] = F::Live($Processor, $Call);
        }

        return $Call['Model'];
    });