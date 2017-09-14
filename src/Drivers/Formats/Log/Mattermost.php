<?php


    setFn('Write', function ($Call)
    {
        $Output =
            [$Call['Channel'].' Channel ('.count($Call['Value']).')   '.$Call['Where']['ID'].PHP_EOL.
            date(DATE_RSS, round(Started)).' *'.$Call['HTTP']['Agent'].'* from *'.$Call['HTTP']['IP'].'*'];

        if (isset($Call['Session']['User']['ID']) && !empty($Call['Session']['User']['ID']))
            $Output[] = PHP_EOL.'User: '.$Call['Session']['User']['ID'].(isset($Call['Session']['User']['Title'])? '('.$Call['Session']['User']['Title'].')': '');

        $Output[] = '***';
        $Output[] = '|Level|Time :watch:|Path|Message|';
        $Output[] = '|:---:|:---:|:---|:---|';
        foreach ($Call['Data'] as $IX => $Row)
        {
            if (is_scalar($Row[2]))
                ;
            else
                $Row[2] = j($Row[2]);
            
            $Output[] = "|".$Call['Mattermosst']['Levels'][$Call['Levels'][$Row[0]]] .' '. $Call['Levels'][$Row[0]]."|".$Row[1]."|".$Row[3]."|".stripslashes($Row[2]);
        }

        $Output = implode(PHP_EOL, $Output);

        return $Output;
    });