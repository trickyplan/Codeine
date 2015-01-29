<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Image API
     * @package Codeine
     * @version 8.x
     */


    setFn('Create', function ($Call)
    {
        return F::Run('Image.Engine.'.$Call['Engine'], null, $Call);
    });

    setFn('Load', function ($Call)
    {
        return F::Run('Image.Engine.'.$Call['Engine'], null, $Call);
    });

    setFn('Pipeline', function ($Call)
    {
        foreach ($Call['Steps'] as $Step)
            $Call['Image'] = F::Run(null, 'Process', $Call, $Step);

        return $Call['Image'];
    });

    setFn('Process', function ($Call)
    {
        list($Process, $Method) = explode(':', $Call['Process']);

        return F::Run('Image.Engine.'.$Call['Engine'].'.'.$Process, $Method, $Call);
    });

    setFn('Save', function ($Call)
    {
        return F::Run('Image.Engine.'.$Call['Engine'], null, $Call);
    });

    setFn('Get', function ($Call)
    {
        return F::Run('Image.Engine.'.$Call['Engine'], null, $Call);
    });