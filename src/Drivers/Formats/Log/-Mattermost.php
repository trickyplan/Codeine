<?php


    setFn('Write', function ($Call)
    {
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
        
        $Output = '***'.PHP_EOL;
        // Формируем шапку таблицы
        $TableHeader = PHP_EOL .'|Level|Time :watch:|Path|Message|'. PHP_EOL .'|:---:|:---:|:---|:---|'. PHP_EOL;
        $Output .= $TableHeader;
        $TotalLen = mb_strlen($Output);

        if (!empty($Call['Data']))
            foreach ($Call['Data'] as $IX => $Row)
            {
                if (is_scalar($Row[3]))
                    ;
                else
                    $Row[3] = trim(json_encode($Row[3], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

                //Обрабатываем перевод строки внутри текста.
                $RowMessages = explode(PHP_EOL,$Row[2]);
                $Amount = count($RowMessages);
                if (!empty($RowMessages) &&  $Amount> 1) {
                    $Row[3] = '';
                    $i = 1;
                    foreach ($RowMessages as $M) {
                        $Row[3] .= $M;
                        if ($i == $Amount)
                            continue;
                        $i++;
                        $Row[3] .= '|'.PHP_EOL.'|-|-|-| ';
                    }
                }

                $Str = "|".$Call['Mattermost']['Levels'][$Call['Levels'][$Row[0]]] .' '. $Call['Levels'][$Row[0]]."|".$Row[1]."|".$Row[4]."|".stripslashes($Row[3])."|".PHP_EOL;
                // Режем вывод по $Call['Mattermost']['Max Lenthgs'] символам.
                $TempLen = mb_strlen($Str);
                if (($TotalLen + $TempLen) > $Call['Mattermost']['Max Length']) {
                    $Diff = $Call['Mattermost']['Max Length'] - $TotalLen;
                    $Output .= str_repeat(' ', $Diff);
                    $Output .= $TableHeader;
                    $TotalLen = mb_strlen($TableHeader);
                }
                $TotalLen += $TempLen;
                $Output .= $Str;
            }

        return $Output;
    });