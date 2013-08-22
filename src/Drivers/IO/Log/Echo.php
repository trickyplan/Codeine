<?php

    setFn('Open', function ($Call)
    {

        return true;
    });

    setFn('Write', function ($Call)
    {
        if ($Call['Renderer']['Service'] == 'View.HTML')
        {
            $Output = '<table class="console">';

            foreach ($Call['Data'] as $IX => $Row)
                $Output .= '<tr class="'.$Call['Levels'][$Row[0]].'">
                        <td>'.$Row[1].'</td>
                        <td>'.($Row[3] == $Call['Data'][$IX-1][3]? '': $Row[3]).'</td>
                        <td>'.stripslashes($Row[2]).'</td>
                        </tr>';

            $Output .= '</table>';

            $Output = preg_replace('/\*(.+)\*/SsUu', '<strong>$1</strong>', $Output);
        }

        return $Output;
    });

    setFn('Close', function ($Call)
    {
        return $Call;
    });