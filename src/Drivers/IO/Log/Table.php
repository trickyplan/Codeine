<?php

    self::setFn('Open', function ($Call)
    {
        return true;
    });

    self::setFn('Write', function ($Call)
    {
        if ($Call['Renderer'] == 'View.HTML')
        {
            echo '<table class="table table-condensed">';

            foreach ($Call['Data'] as $Message)
                echo '<tr><td>'.$Message[2].'</td><td>'.$Message[0].'</td><td>'.$Message[1]."</td></tr>";

            echo '</table>';
        }

        return $Call;
    });

    self::setFn('Close', function ($Call)
    {

        return $Call;
    });