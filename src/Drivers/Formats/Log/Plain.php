<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Output =
            $Call['Channel'].' Channel ('.count($Call['Value']).')'.PHP_EOL.
            date(DATE_RSS, round(Started)).' *'.$Call['HTTP']['User Agent'].'* from *'.$Call['HTTP']['IP'].'*'.PHP_EOL;

        foreach ($Call['Data'] as $IX => $Row)
            $Output .= $Call['Levels'][$Row[0]]."\t".$Row[1]."\t".($Row[3] == (isset($Call['Value'][$IX-1][3])? $Call['Value'][$IX-1][3]: 0)? '': $Row[3])."\t".stripslashes($Row[2]).PHP_EOL;

        $Output = preg_replace('/\*(.*)\*/SsUu', '$1', $Output);

        return $Output;
    });