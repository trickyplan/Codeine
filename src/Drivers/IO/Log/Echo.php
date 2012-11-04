<?php

    setFn('Open', function ($Call)
    {
        if ($Call['Renderer'] == 'View.HTML')
            echo '<pre>';
        return true;
    });

    setFn('Write', function ($Call)
    {
        if ($Call['Renderer'] == 'View.HTML')
            echo $Call['Data'][0]."\t\t".$Call['Data'][1]."\n";
        return true;
    });

    setFn('Close', function ($Call)
    {
        if ($Call['Renderer'] == 'View.HTML')
            echo '</pre>';
        return $Call;
    });