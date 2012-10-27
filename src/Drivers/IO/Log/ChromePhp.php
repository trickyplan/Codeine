<?php

    require Codeine.'/Vendor/ccampbell/chromephp/ChromePhp.php';

    self::setFn('Open', function ($Call)
    {
        return ChromePhp::getInstance();
    });

    self::setFn('Write', function ($Call)
    {
        foreach ($Call['Data'] as $Message)
        {
            switch($Message[2])
            {
                case 'Error':
                    $Call['Link']->error($Message[0].': '.$Message[1]);
                break;

                case 'Begin':
                    $Call['Link']->group($Message[0].': '.$Message[1]);
                break;

                case 'End':
                    $Call['Link']->groupEnd($Message[0].': '.$Message[1]);
                break;

                default:
                    $Call['Link']->log($Message[0].': '.$Message[1]);
                break;
            }
        }

        return $Call;
    });

    self::setFn('Close', function ($Call)
    {

        return $Call;
    });