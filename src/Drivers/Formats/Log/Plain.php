<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Output =
            [$Call['Channel'].' Channel ('.count($Call['Value']).')'.PHP_EOL.
            $Call['Where']['ID'].PHP_EOL.
            date(DATE_RSS, round(Started)).' *'.$Call['HTTP']['Agent'].'* from *'.$Call['HTTP']['IP'].'*'];

        if (isset($Call['Session']['User']['ID']) && $Call['Session']['User']['ID']>0)
            $Output[] = PHP_EOL.'User: '.$Call['Session']['User']['ID'].(isset($Call['Session']['User']['Title'])? '('.$Call['Session']['User']['Title'].')': '');

        foreach ($Call['Data'] as $IX => $Row)
        {
            if (is_scalar($Row[2]))
                ;
            else
                $Row[2] = j($Row[2]);
            
            $Output[] = $Call['Levels'][$Row[0]]."\t".$Row[1]."\t".$Row[3]."\t".stripslashes($Row[2]);
        }

        $Output = implode(PHP_EOL, $Output);
        $Output = preg_replace('/\*(.*)\*/SsUu', '$1', $Output);

        return $Output;
    });