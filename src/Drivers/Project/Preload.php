<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeRequestRun', function ($Call) {
        return F::Apply(null, 'Load.ProjectAndVersion', $Call);
    });

    setFn('beforeCLIRequestRun', function ($Call) {
        return F::Apply(null, 'Load.ProjectAndVersion', $Call);
    });

    setFn('Load.ProjectAndVersion', function ($Call) {
        $Call['Project'] = F::loadOptions('Project');
        $Call['Version'] = F::loadOptions('Version');
        F::Log('Project Version: *' . F::Dot($Call, 'Version.Project') . '*', LOG_INFO);
        return $Call;
    });