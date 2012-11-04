<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Code', function ($Call)
    {
        // FIX THIS SHIT
    // TODO Realize "Code" function
        $chars = $Call['Alphabet']; // Задаем символы, используемые в капче. Разделитель использовать не надо.
          $length = rand(4, 4); // Задаем длину капчи, в нашем случае - от 4 до 7
          $numChars = strlen($chars); // Узнаем, сколько у нас задано символов
          $str = '';
          for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, rand(1, $numChars) - 1, 1);
          } // Генерируем код

        // Перемешиваем, на всякий случай
            $array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
            srand ((float)microtime()*1000000);
            shuffle ($array_mix);
        // Возвращаем полученный код
        return implode("", $array_mix);
    });

    setFn('Widget', function ($Call)
    {
        $Code = F::Run(null, 'Code', $Call);

        $Image = imagecreatetruecolor(100, 25);

        imagefill($Image, 0, 0, imagecolorallocate($Image, 255,255,255));

        $Codes = str_split($Code, 1);

        foreach ($Codes as $IX => $Char)
            imagettftext($Image, rand (10,14), rand(-25,25), 20+$IX*15, 15, imagecolorallocate($Image, 0,0,0), F::findFile('Assets/droid/ttf/DroidSans.ttf'), $Char);

        imagepng($Image, Root.'/Public/captcha/'.sha1($Code).'.png');

        $Call['Session']['CAPTCHA'] = sha1($Code);

        return $Call;
    });

    setFn('Check', function ($Call)
    {
        unlink(Root.'/Public/captcha/'.$Call['Request']['CAPTCHA.Challenge'].'.png');
        if ($Call['Request']['CAPTCHA.Challenge'] != sha1($Call['Request']['CAPTCHA.Answer']))
        {
            $Call['Failure'] = true;
            $Call = F::Hook('CAPTCHA.Failed', $Call);
        }

        return $Call;
    });