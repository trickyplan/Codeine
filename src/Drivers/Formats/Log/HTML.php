<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        $Header = $Call['Channel'].' Channel ('.count($Call['Value']).')</td></tr><tr><td colspan="3">'.date(DATE_RSS, round(Started)).PHP_EOL.'*'.$Call['HTTP']['User Agent'].'* from *'.$Call['HTTP']['IP'].'*';

        if (isset($Call['Session']['User']['ID']))
            $Header.= PHP_EOL.'User: '.$Call['Session']['User']['ID'].(isset($Call['Session']['User']['Title'])? '('.$Call['Session']['User']['Title'].')': '');

        if (is_array($Call['Value']))
        {
            $Output = '<table class="console"><tr class="'.$Call['Levels'][11].'"><td colspan="3">'.$Header.'</td></tr>';

            foreach ($Call['Value'] as $IX => $Row)
                $Output .= '<tr class="'.$Call['Levels'][$Row[0]].'">
                        <td>'.sprintf('%.3f', $Row[1]).'</td>
                        <td>'.($Row[3] == (isset($Call['Value'][$IX-1][3])? $Call['Value'][$IX-1][3]: false)? '': $Row[3]).'</td>
                        <td class="indent-'.($Row[4]+1).'">'.stripslashes(htmlentities($Row[2])).'</td>
                        </tr>';
            $Output .= '</table>';
        }
        else
            $Output = $Header.PHP_EOL.$Call['Value'];

        $Output = preg_replace('/\*(.*)\*/SsUu', '<strong class="strong">$1</strong>', $Output);

        return $Output;
    });