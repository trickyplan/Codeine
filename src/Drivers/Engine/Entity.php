<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Create', function ($Call)
    {
        $Model = F::Run('Engine.Entity', 'Model', $Call);

        // TODO Heterogenic mapping

        $ID = F::Run('Engine.IO', 'Write',
            array (
                  'Storage' => $Model['Storage'],
                  'Data' => $Call['Data']
            ));

        return $Call;
    });

    self::setFn('Read', function ($Call)
    {

        return $Call;
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