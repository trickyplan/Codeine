<?php


    setFn('Write', function ($Call)
    {
        $Output =
            $Call['Channel'].' Channel ('.count($Call['Value']).')   '.$Call['Where']['ID'].PHP_EOL.
            date(DATE_RSS, round(Started)).' *'.$Call['HTTP']['Agent'].'* from *'.$Call['HTTP']['IP'].'*'.PHP_EOL;

        if (isset($Call['Session']['User']['ID']) && !empty($Call['Session']['User']['ID']))
            $Output .= PHP_EOL.'User: '.$Call['Session']['User']['ID'].
                        (isset($Call['Session']['User']['Title'])? '('.$Call['Session']['User']['Title'].')': '').PHP_EOL;
        // Добавляем линию
        $Output .= '***'.PHP_EOL;
        // Формируем шапку таблицы
        $TableHeader = PHP_EOL .'|Level|Time :watch:|Path|Message|'. PHP_EOL .'|:---:|:---:|:---|:---|'. PHP_EOL;
        $Output .= $TableHeader;
        $TotalLen = mb_strlen($Output);

        foreach ($Call['Data'] as $IX => $Row)
        {
            if (is_scalar($Row[2]))
                ;
            else
                $Row[2] = trim(json_encode($Row[2], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            //Обрабатываем перевод строки внутри текста.
            $RowMessages = explode(PHP_EOL,$Row[2]);
            $Amount = count($RowMessages);
            if (!empty($RowMessages) &&  $Amount> 1) {
                $Row[2] = '';
                $i = 1;
                foreach ($RowMessages as $M) {
                    $Row[2] .= $M;
                    if ($i == $Amount)
                        continue;
                    $i++;
                    $Row[2] .= '|'.PHP_EOL.'|-|-|-| ';
                }
            }

            $Str = "|".$Call['Mattermosst']['Levels'][$Call['Levels'][$Row[0]]] .' '. $Call['Levels'][$Row[0]]."|".$Row[1]."|".$Row[3]."|".stripslashes($Row[2])."|".PHP_EOL;
            // Режем вывод по $Call['Mattermosst']['Max Lenthgs'] символам.
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