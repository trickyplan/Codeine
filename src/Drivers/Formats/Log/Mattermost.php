<?php


    setFn('Write', function ($Call)
    {
        $Output =
            $Call['Channel'].' Channel ('.count($Call['Value']).')   '.$Call['Where']['ID'].PHP_EOL.
            date(DATE_RSS, round(Started)).' *'.$Call['HTTP']['Agent'].'* from *'.$Call['HTTP']['IP'].'*'.PHP_EOL;

        if (isset($Call['Session']['User']['ID']) && !empty($Call['Session']['User']['ID']))
            $Output .= PHP_EOL.'User: '.$Call['Session']['User']['ID'].
                        (isset($Call['Session']['User']['Title'])? '('.$Call['Session']['User']['Title'].')': '').PHP_EOL;

        $Output .= '***'.PHP_EOL;
        $TableHeader = PHP_EOL .'|Level|Time :watch:|Path|Message|'. PHP_EOL .'|:---:|:---:|:---|:---|'. PHP_EOL;
        $Output .= $TableHeader;
        $TotalLen = mb_strlen($Output);

        foreach ($Call['Data'] as $IX => $Row)
        {
            if (is_scalar($Row[2]))
                ;
            else
                $Row[2] = j($Row[2]);

            $Str = "|".$Call['Mattermosst']['Levels'][$Call['Levels'][$Row[0]]] .' '. $Call['Levels'][$Row[0]]."|".$Row[1]."|".$Row[3]."|".stripslashes($Row[2]).PHP_EOL;
            $TempLen = mb_strlen($Str);
            if (($TotalLen + $TempLen) > $Call['Mattermosst']['Max Lenthgs']) {
                $Diff = $Call['Mattermosst']['Max Lenthgs'] - $TotalLen;
                $Output .= str_repeat(' ', $Diff);
                $Output .= $TableHeader;
                $TotalLen = mb_strlen($TableHeader);
            }
            $TotalLen += $TempLen;
            $Output .= $Str;
         }

        return $Output;
    });