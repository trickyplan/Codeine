<?php

    self::setFn('Open', function ($Call)
    {
        return true;
    });

    self::setFn('Write', function ($Call)
    {
        if ($Call['Renderer'] == 'View.HTML')
        {
            echo '<pre>';

            foreach ($Call['Data'] as $Message)
                echo '['.$Message[2].']'.$Message[0].':'.$Message[1]."\n";

            echo '</pre>';
        }

        return $Call;
    });

    self::setFn('Close', function ($Call)
    {

        return $Call;
    });