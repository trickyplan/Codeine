<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Output = [];

        foreach ($Call['Data'] as $IX => $Row)
        {
            if (is_scalar($Row[2]))
                ;
            else
                $Row[2] = j($Row[2]);
            
            $Output[] = '['.REQID.'] V'.$Row[0]."\t".$Row[1]."\t".$Row[3]."\t".stripslashes($Row[2]);
        }

        $Output = implode(PHP_EOL, $Output).PHP_EOL;
        
        // $Output = preg_replace('/\*(.*)\*/SsUu', '$1', $Output);

        return $Output;
    });