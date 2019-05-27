<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (F::Dot($Call, 'JS.Compress.Closure.Enabled'))
        {
            $Pipes = [];

            $ClosureCompressor = proc_open(
                $Call['JS']['Compress']['Closure']['Command'],
                $Call['JS']['Compress']['Closure']['Descriptors'],
                $Pipes);

            $Compressed = '';
            
            if (is_resource($ClosureCompressor))
            {
                fwrite($Pipes[0], $Call['JS']['Source']);
                fclose($Pipes[0]);
                
                while (!feof($Pipes[1]))
                    $Compressed .= fread($Pipes[1], 8192);
                
                fclose($Pipes[1]);

                if (($Code = proc_close($ClosureCompressor)) == 0)
                {
                    $OriginalSize = mb_strlen($Call['JS']['Source']);
                    $CompressedSize = mb_strlen($Compressed);
                    F::Log('JS Code is compressed by Closure: From '.$OriginalSize.' to '.$CompressedSize.' bytes ('.round(100*(1-$CompressedSize/$OriginalSize)).'%)', LOG_INFO, 'Performance');
                    
                    if (F::Dot($Call, 'JS.Compress.Closure.Expose'))
                        $Compressed = '/* Compressed by Closure*/'.PHP_EOL.$Compressed;
                    
                    $Call['JS']['Source'] = $Compressed;
                }
                else
                {
                    F::Log('JS Code is not compressed by Closure', LOG_INFO, 'Performance');
                    F::Log('Return code for '.$Call['JS']['Compress']['Closure']['Command'].': '.$Code, LOG_INFO, 'Performance');

                    if ($Call['JS']['Compress']['Closure']['Disable On Error'])
                    {
                        $Call['JS']['Compress']['Closure']['Enabled'] = false; // Codeine Magic: Dynamic Reconfiguration FGJ
                        F::Log('Disabled by error', LOG_WARNING, 'Performance');
                        if (F::Dot($Call, 'JS.Compress.Closure.Expose'))
                            $Call['JS']['Source'] = '/* Closure Disabled By Error */'.PHP_EOL.$Call['JS']['Source'];
                    }
                }
            }
            else
                F::Log('[Closure] Not resource', LOG_WARNING, 'Performance');
        }

        return $Call;
    });