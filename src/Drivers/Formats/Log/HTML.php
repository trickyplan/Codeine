<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        $Header = $Call['Channel'].' Channel ('.count($Call['Value']).')</td></tr><tr><td colspan="3">'.date(DATE_RSS, round(Started)).' *'.$Call['UA'].'* from *'.$Call['IP'].'*';

        if (is_array($Call['Value']))
        {
            $Output = '<table class="console"><tr class="'.$Call['Levels'][11].'"><td colspan="3">'.$Header.'</td></tr>';

            foreach ($Call['Value'] as $IX => $Row)
                $Output .= '<tr class="'.$Call['Levels'][$Row[0]].'">
                        <td>'.$Row[1].'</td>
                        <td>'.($Row[3] == (isset($Call['Value'][$IX-1][3])? $Call['Value'][$IX-1][3]: false)? '': $Row[3]).'</td>
                        <td>'.stripslashes(htmlentities($Row[2])).'</td>
                        </tr>';
            $Output .= '</table>';
        }
        else
            $Output = $Header.PHP_EOL.$Call['Value'];

        if ($Call['Asterisk Support'])
            $Output = preg_replace('/\*(.*)\*/SsUu', '<strong>$1</strong>', $Output);

        return $Output;
    });