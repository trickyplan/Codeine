<?php

    /* Codeine
     * @author BreathLess
     * @description Image API
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Create', function ($Call)
    {
        return F::Run('Image.Engine.'.$Call['Engine'], null, $Call);
    });

    self::setFn('Load', function ($Call)
    {
        return F::Run('Image.Engine.'.$Call['Engine'], null, $Call);
    });

    self::setFn('Pipeline', function ($Call)
    {
        foreach ($Call['Steps'] as $Step)
            $Call['Image'] = F::Run(null, 'Process', $Call, $Step);

        return $Call['Image'];
    });

    self::setFn('Process', function ($Call)
    {
        list($Process, $Method) = explode(':', $Call['Process']);

        return F::Run('Image.Engine.'.$Call['Engine'].'.'.$Process, $Method, $Call);
    });

    self::setFn('Save', function ($Call)
    {
        return F::Run('Image.Engine.'.$Call['Engine'], null, $Call);
    });