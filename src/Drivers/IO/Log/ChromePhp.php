<?php

    require Codeine.'/Vendor/ccampbell/chromephp/ChromePhp.php';

    setFn('Open', function ($Call)
    {
        return ChromePhp::getInstance();
    });

    setFn('Write', function ($Call)
    {
            switch($Call['Data'][2])
            {
                case 'Error':
                    $Call['Link']->error($Call['Data'][0].': '.$Call['Data'][1]);
                break;

                case 'Begin':
                    $Call['Link']->group($Call['Data'][0].': '.$Call['Data'][1]);
                break;

                case 'End':
                    $Call['Link']->groupEnd($Call['Data'][0].': '.$Call['Data'][1]);
                break;

                default:
                    $Call['Link']->log($Call['Data'][0].': '.$Call['Data'][1]);
                break;
            }

        return true;
    });

    setFn('Close', function ($Call)
    {

        return $Call;
    });