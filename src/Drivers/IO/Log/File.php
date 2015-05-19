<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Console Object Support
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        $Filename = $Call['Directory'].DS.$Call['Scope'].$Call['Log']['File']['Extension'];

        if (file_exists($Filename))
            return fopen($Filename, $Call['Log']['File']['Mode']);
        else
        {
            $DirName = dirname($Filename);

            if (!file_exists($DirName) || !is_dir($DirName))
            {
                if (mkdir($DirName, 0777, true)) // Fuck PHP
                    F::Log('Directory '.$DirName.' created with mode '.$Call['IO']['Directory']['Create Mode'], LOG_INFO, 'Administrator');
                else
                {
                    F::Log('Directory '.$DirName.' cannot created', LOG_ERR, 'Administrator');
                    return null;
                }
            }
            else
                F::Log('Directory '.$DirName.' already exists', LOG_INFO, 'Administrator');

            return fopen($Filename, d(__FILE__, __LINE__, $Call['Log']['File']['Mode']));
        }
    });

    setFn('Write', function ($Call)
    {
        if (is_resource($Call['Link']))
            return fwrite($Call['Link'], $Call['Data']);
        else
            return null;
    });

    setFn('Close', function ($Call)
    {
        if (is_resource($Call['Link']))
            return fclose($Call['Link']);
        else
            return null;
    });

    setFn('Size', function ($Call)
    {
        return filesize($Call['Directory'].DS.$Call['Scope'].$Call['Log']['File']['Extension']);
    });