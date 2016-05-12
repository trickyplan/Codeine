<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if ($Call['CSS']['Compress']['YUI']['Enabled'])
        {
            $Pipes = [];

            $YUICompressor = proc_open(
                $Call['CSS']['Compress']['YUI']['Command'],
                $Call['CSS']['Compress']['YUI']['Descriptors'],
                $Pipes);

            if (is_resource($YUICompressor))
            {
                if ($Call['CSS']['Compress']['YUI']['Expose'])
                    $Compressed = '/* YUI Compressed */'.PHP_EOL;
                else
                    $Compressed = '';

                fwrite($Pipes[0], $Call['CSS']['Source']);
                fclose($Pipes[0]);
                $Compressed .= stream_get_contents($Pipes[1]);
                fclose($Pipes[1]);

                if (($Code = proc_close($YUICompressor)) == 0)
                {
                    $Call['CSS']['Source'] = $Compressed;
                    F::Log('[YUI] '.$Call['CSS']['Fullpath'].' compressed', LOG_DEBUG);
                }
                else
                {
                    F::Log('[YUI] '.$Call['CSS']['Fullpath'].' not compressed', LOG_INFO);
                    F::Log('Return code for '.$Call['CSS']['Compress']['YUI']['Command'].': '.$Code, LOG_DEBUG);

                    if ($Call['CSS']['Compress']['YUI']['Disable On Error'])
                    {
                        $Call['CSS']['Compress']['YUI']['Enabled'] = false; // Codeine Magic: Dynamic Reconfiguration FGJ
                        F::Log('[YUI] Disabled by error', LOG_INFO);
                    }
                }
            }
            else
                F::Log('[YUI] Not resource', LOG_WARNING);
        }

        return $Call;
    });