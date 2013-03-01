<?php

    setFn('Open', function ($Call)
    {

        return true;
    });

    setFn('Write', function ($Call)
    {
        if ($Call['Renderer'] == 'View.HTML')
            echo '<pre>'.$Call['Data'].'</pre>';

        return true;
    });

    setFn('Close', function ($Call)
    {
        return $Call;
    });