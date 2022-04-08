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

    setFn('Load.ProjectAndVersion', function ($Call)
    {
        $Call['Project'] = F::loadOptions('Project');

        $CodeineComposerFile = jd(file_get_contents(Root.'/vendor/trickyplan/codeine/composer.json'));

        if (isset($CodeineComposerFile['version']))
            $Call['Version']['Codeine'] = $CodeineComposerFile['version'];
        else
            $Call['Version']['Codeine'] = 'Live';

        $ProjectComposerFile = jd(file_get_contents(Root.'/composer.json'));

        if (isset($ProjectComposerFile['version']))
            $Call['Version']['Project'] = $ProjectComposerFile['version'];
        else
            $Call['Version']['Project'] = 'Live';

        F::Log('Project Version: *' . F::Dot($Call, 'Version.Project') . '*', LOG_INFO);
        return $Call;
    });