<?php

    self::setFn('Open', function ($Call)
    {
        if ($Call['Renderer'] == 'View.HTML')
            echo '<pre>';
        return true;
    });

    self::setFn('Write', function ($Call)
    {
        echo $Call['Data'][0]."\t\t".$Call['Data'][1]."\n";
        return true;
    });

    self::setFn('Close', function ($Call)
    {
        if ($Call['Renderer'] == 'View.HTML')
            echo '</pre>';
        return $Call;
    });