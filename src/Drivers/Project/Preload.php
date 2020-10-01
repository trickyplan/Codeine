<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeRequestRun', function ($Call)
    {
        return F::Apply(null, 'Load.Project.Version', $Call);
     });
     
    setFn('beforeCLIRequestRun', function ($Call)
    {
        return F::Apply(null, 'Load.Project.Version', $Call);
    });

    setFn('Load.Project.Version', function ($Call)
    {
        $Call['Version'] = F::loadOptions('Version');
        F::Log('Project Version: *'.F::Dot($Call, 'Version.Project.Major').'*', LOG_INFO);
        return $Call;
    });