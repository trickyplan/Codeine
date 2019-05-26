<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (F::Dot($Call, 'JS.Compress.UglifyJS.Enabled'))
        {
            $Pipes = [];

            $UglifyJSCompressor = proc_open(
                $Call['JS']['Compress']['UglifyJS']['Command'],
                $Call['JS']['Compress']['UglifyJS']['Descriptors'],
                $Pipes);
            
            $Compressed = '';

            if (is_resource($UglifyJSCompressor))
            {
                fwrite($Pipes[0], $Call['JS']['Source']);
                fclose($Pipes[0]);
                while (!feof($Pipes[1]))
                    $Compressed .= fread($Pipes[1], 8192);
                
                fclose($Pipes[1]);

                if (($Code = proc_close($UglifyJSCompressor)) == 0)
                {
                    $OriginalSize = mb_strlen($Call['JS']['Source']);
                    $CompressedSize = mb_strlen($Compressed);
                    F::Log('JS Code is compressed by UglifyJS: From '.$OriginalSize.' to '.$CompressedSize.' bytes ('.round(100*(1-$CompressedSize/$OriginalSize)).'%)', LOG_INFO, 'Performance');
                    
                    if (F::Dot($Call, 'JS.Compress.UglifyJS.Expose'))
                        $Compressed = '/* Compressed by UglifyJS */'.PHP_EOL.$Compressed;
                    
                    $Call['JS']['Source'] = $Compressed;
                }
                else
                {
                    F::Log('JS Code is not compressed by UglifyJS', LOG_INFO);
                    F::Log('Return code for '.$Call['JS']['Compress']['UglifyJS']['Command'].': '.$Code, LOG_INFO, 'Performance');

                    if ($Call['JS']['Compress']['UglifyJS']['Disable On Error'])
                    {
                        $Call['JS']['Compress']['UglifyJS']['Enabled'] = false; // Codeine Magic: Dynamic Reconfiguration FGJ
                        F::Log('Disabled by error', LOG_INFO, 'Performance');
                        if (F::Dot($Call, 'JS.Compress.UglifyJS.Expose'))
                            $Call['JS']['Source'] = '/* UglifyJS Disabled By Error */'.PHP_EOL.$Call['JS']['Source'];
                    }
                }
            }
            else
                F::Log('[UglifyJS] Not resource', LOG_WARNING, 'Performance');
        }

        return $Call;
    });