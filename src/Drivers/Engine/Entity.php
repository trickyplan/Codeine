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
        $Created = array('User' => 1); // FIXME

        foreach ($Model['Nodes'] as $Name => $Node)
            if (isset($Call['Data'][$Name]))
                $Created[$Name] = $Call['Data'][$Name];

        $ID = F::Run('Engine.IO', 'Write',
            array (
                  'Storage' => $Model['Storage'],
                  'Scope' => $Call['Entity'],
                  'Data' => $Created
            ));

        return $Call;
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

        return $Call;
    });

    self::setFn('Delete', function ($Call)
    {

        return $Call;
    });

    self::setFn('Model', function ($Call)
    {
        // TODO Realize Model
        return F::loadOptions('Entity.'.$Call['Entity']);
    });