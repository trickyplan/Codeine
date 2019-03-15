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

        /*
            0 $Verbose,
            1 $Time,
            2 $Hash,
            3 $Message,
            4 $From,
            5 $StackDepth,
            6 F::Stack(),
            7 self::getColor()
        */
        
        if (!empty($Call['Data']))
            foreach ($Call['Data'] as $IX => $Row)
            {
                if (is_scalar($Row[3]))
                    ;
                else
                    $Row[3] = j($Row[3]);
                
                $Output[] = implode("\t", ['['.REQID.']', 'V'.$Row[0], $Row[1], $Row[2], stripslashes($Row[3])]);
            }

        $Output = implode(PHP_EOL, $Output).PHP_EOL;
        
        // $Output = preg_replace('/\*(.*)\*/SsUu', '$1', $Output);

        return $Output;
    });