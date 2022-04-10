<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Load', function ($Call)
    {
        $Call['Project'] = F::loadOptions('Project');

        $ProjectComposerFile = jd(file_get_contents(Root.'/composer.json'));

        if (isset($ProjectComposerFile['version']))
            $Call['Version']['Project'] = $ProjectComposerFile['version'];
        else
            $Call['Version']['Project'] = 'Live';

        F::Log('Project Version: *' . F::Dot($Call, 'Version.Project') . '*', LOG_INFO);

        if (file_exists(Root.'/vendor/trickyplan/codeine/composer.json'))
        {
            $CodeineComposerFile = jd(file_get_contents(Root.'/vendor/trickyplan/codeine/composer.json'));

            if (isset($CodeineComposerFile['version']))
                $Call['Version']['Codeine'] = $CodeineComposerFile['version'];
            else
                $Call['Version']['Codeine'] = 'Live';
        }
        else // we are codeine
            $Call['Version']['Codeine'] = $Call['Version']['Project'];

        return $Call;
    });