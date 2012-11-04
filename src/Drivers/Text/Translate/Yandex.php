<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        include Codeine.'/Vendor/yandex/translate/Yandex_Translate.php';

        $translator = new Yandex_Translate();
        return $translator->yandexTranslate($Call['From'], $Call['To'], $Call['Value']);
    });
