<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if ($Call['JS']['Compress']['JSMin']['Enabled'])
        {
            $Pipes = [];

            $JSMinCompressor = proc_open(
                $Call['JS']['Compress']['JSMin']['Command'],
                $Call['JS']['Compress']['JSMin']['Descriptors'],
                $Pipes);

            if (is_resource($JSMinCompressor))
            {
                if ($Call['JS']['Compress']['JSMin']['Expose'])
                    $Compressed = '// JSMin Compressed'.PHP_EOL;
                else
                    $Compressed = '';

                fwrite($Pipes[0], $Call['JS']['Source']);
                fclose($Pipes[0]);
                $Compressed .= trim(stream_get_contents($Pipes[1]));
                fclose($Pipes[1]);

                if (($Code = proc_close($JSMinCompressor)) == 0)
                {
                    $Call['JS']['Source'] = $Compressed;
                    F::Log('[JSMin] '.$Call['JS']['Fullpath'].' compressed', LOG_DEBUG);
                }
                else
                {
                    F::Log('[JSMin] '.$Call['JS']['Fullpath'].' not compressed', LOG_INFO);
                    F::Log('Return code for '.$Call['JS']['Compress']['JSMin']['Command'].': '.$Code, LOG_DEBUG);

                    if ($Call['JS']['Compress']['JSMin']['Disable On Error'])
                    {
                        $Call['JS']['Compress']['JSMin']['Enabled'] = false; // Codeine Magic: Dynamic Reconfiguration FGJ
                        F::Log('[JSMin] Disabled by error', LOG_INFO);
                    }
                }
            }
            else
                F::Log('[JSMin] Not resource', LOG_WARNING);
        }

        return $Call;
    });