<?php

    function F_Locale_Hook()
    {
        if (Client::$Level > 0)
        {
            if (null !== ($Language = Client::$Agent->Get('Language')))
                Client::$Language = $Language;
            else
                Client::$Language = 'ru_RU';

            setlocale(LC_ALL, Client::$Language.'.UTF-8');
        }

        return true;
    }
