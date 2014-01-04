<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Output = '<table class="console">
        <tr class="'.$Call['Levels'][11].'"><td colspan="3">'.$Call['Channel'].' Channel ('.count($Call['Logs']).')</td></tr>';

        foreach ($Call['Logs'] as $IX => $Row)
            $Output .= '<tr class="'.$Call['Levels'][$Row[0]].'">
                    <td>'.$Row[1].'</td>
                    <td>'.($Row[3] == (isset($Call['Data'][$IX-1][3])? $Call['Data'][$IX-1][3]: 0)? '': $Row[3]).'</td>
                    <td>'.stripslashes(htmlentities($Row[2])).'</td>
                    </tr>';

        $Output .= '</table>';

        if ($Call['Asterisk Support'])
            $Output = preg_replace('/\*(.*)\*/SsUu', '<strong>$1</strong>', $Output);

        return $Output;
    });