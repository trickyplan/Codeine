<?php

    setFn('Open', function ($Call)
    {

        return true;
    });

    setFn('Write', function ($Call)
    {
        if ($Call['Renderer'] == 'View.HTML')
        {
            $Output = '<table class="console">';

            foreach ($Call['Data'] as $IX => $Row)
                $Output .= '<tr class="'.$Call['Levels'][$Row[0]].'">
                        <td>'.$Row[1].'</td>
                        <td>'.($Row[3] == $Call['Data'][$IX-1][3]? '': $Row[3]).'</td>
                        <td>'.$Row[2].'</td>
                        </tr>';

            $Output .= '</table>';

            echo $Output;
        }
        else
        {
            $Output = '';

            foreach ($Call['Data'] as $IX => $Row)
                $Output .= $Call['Levels'][$Row[0]]
                        ."\t".$Row[1]
                        ."\t".($Row[3] == $Call['Data'][$IX-1][3]? '': $Row[3])
                        ."\t".$Row[2];

            echo $Output;
        }

        return true;
    });

    setFn('Close', function ($Call)
    {
        return $Call;
    });