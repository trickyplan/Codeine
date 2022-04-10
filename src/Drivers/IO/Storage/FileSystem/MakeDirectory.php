<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        $DirName = dirname($Call['Filename']);

        $MakeDirectory = false;

        if (file_exists($DirName)) {
            if (is_dir($DirName)) {
                F::Log('Directory ' . $DirName . ' already exists', LOG_DEBUG, 'Administrator');
            } else {
                F::Log('File ' . $DirName . ' already exists, removing', LOG_INFO, 'Administrator');
                unlink($DirName);
                $MakeDirectory = true;
            }
        } else {
            F::Log('Directory ' . $DirName . ' doesn\'t exists', LOG_INFO, 'Administrator');
            $MakeDirectory = true;
        }

        if ($MakeDirectory) {
            F::Log(
                'Directory ' . $DirName . ' will be created with mode ' . $Call['IO']['FileSystem']['Create Mode'],
                LOG_INFO,
                'Administrator'
            );
            if (mkdir($DirName, 0777, true)) { // Fuck PHP
                F::Log(
                    'Directory ' . $DirName . ' created with mode ' . $Call['IO']['FileSystem']['Create Mode'],
                    LOG_INFO,
                    'Administrator'
                );
            } else {
                F::Log('Directory ' . $DirName . ' cannot created', LOG_ERR, 'Administrator');
            }
        }

        return $Call;
    });
