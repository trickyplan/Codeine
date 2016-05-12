<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if ($Call['JS']['Compress']['YUI']['Enabled'])
        {
            $Pipes = [];

            $YUICompressor = proc_open(
                $Call['JS']['Compress']['YUI']['Command'],
                $Call['JS']['Compress']['YUI']['Descriptors'],
                $Pipes);

            if (is_resource($YUICompressor))
            {
                if ($Call['JS']['Compress']['YUI']['Expose'])
                    $Compressed = '/* YUI Compressed */'.PHP_EOL;
                else
                    $Compressed = '';

                fwrite($Pipes[0], $Call['JS']['Source']);
                fclose($Pipes[0]);
                $Compressed .= stream_get_contents($Pipes[1]);
                fclose($Pipes[1]);

                if (($Code = proc_close($YUICompressor)) == 0)
                {
                    $Call['JS']['Source'] = $Compressed;
                    F::Log('[YUI] '.$Call['JS']['Fullpath'].' compressed', LOG_DEBUG);
                }
                else
                {
                    F::Log('[YUI] '.$Call['JS']['Fullpath'].' not compressed', LOG_INFO);
                    F::Log('Return code for '.$Call['JS']['Compress']['YUI']['Command'].': '.$Code, LOG_DEBUG);

                    if ($Call['JS']['Compress']['YUI']['Disable On Error'])
                    {
                        $Call['JS']['Compress']['YUI']['Enabled'] = false; // Codeine Magic: Dynamic Reconfiguration FGJ
                        F::Log('[YUI] Disabled by error', LOG_INFO);
                    }
                }
            }
            else
                F::Log('[YUI] Not resource', LOG_WARNING);
        }

        return $Call;
    });