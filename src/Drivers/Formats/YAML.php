<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     */

    setFn('Read', function ($Call)
    {
        $Result = null;
        if (empty($Call['Value']))
            F::Log('YAML: Empty', LOG_NOTICE);
        else
            $Result = yaml_parse($Call['Value'], F::Dot($Call, 'YAML.Position'));

        return $Result;
    });

    setFn('Write', function ($Call)
    {
        return yaml_emit($Call['Value'], F::Dot($Call, 'YAML.Encoding'), F::Dot($Call, 'YAML.LineBreak'));
    });

    setFn('Write.Call', function ($Call)
    {
        $Call['Value'] = yaml_emit($Call['Value'], F::Dot($Call, 'YAML.Encoding'), F::Dot($Call, 'YAML.LineBreak'));
        return $Call;
    });
