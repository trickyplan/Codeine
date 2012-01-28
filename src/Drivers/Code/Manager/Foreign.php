<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('List.Update', function ($Call)
    {
        $Packages = array();

        foreach ($Call['Repository'] as $Repo)
        {
            $RepoPackages = json_decode(file_get_contents($Repo.'/Packages.json'), true);

            foreach($RepoPackages as $ID => $Package)
            {
                $Package['Origin'] = $Repo;
                $Packages[$Package['Name']][$Package['Origin']] = $Package;
            }
        }

        d(__FILE__, __LINE__, $Packages);
        file_put_contents(Root.'/Data/Foreign.json', json_encode($Packages));

        $Call['Widgets'] = array();
        return $Call;
    });