<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if (isset($Call['Channel']))
            ;
        else
            $Call['Channel'] = 'Undefined Channel';

        if (PHP_SAPI == 'cli')
        {
            $UserString = posix_getpwuid(posix_getuid())['name'].' from CLI ';

            if (empty($SSH = shell_exec('echo $SSH_CLIENT')))
                ;
            else
                $UserString.= 'SSH from: '.$SSH;
        }
        else
            $UserString = '*'.$Call['HTTP']['Agent'].'* from *'.$Call['HTTP']['IP'].'*';

        $BySeverity = [];
        
        foreach ($Call['Value'] as $IX => $Row)
            if (isset($BySeverity[$Row[0]]))
                $BySeverity[$Row[0]]++;
            else
                $BySeverity[$Row[0]] = 1;
        
        $SeverityStatistics = [];
        foreach ($Call['Levels'] as $Severity => $Label)
        {
            if (isset($BySeverity[$Severity]))
                $SeverityStatistics[$Severity] = $Label.': *'.$BySeverity[$Severity].'*';
        }
            
        $Header = $Call['Channel'].' Channel ('.count($Call['Value']).' messages, '.implode(', ', $SeverityStatistics).')'
            .PHP_EOL
            .'Request ID: *'.REQID.'*'
            .PHP_EOL
            .date(DATE_RSS, round(Started))
            .PHP_EOL
            .$UserString;

        if (isset($Call['Session']['User']['ID']) && $Call['Session']['User']['ID']>0)
            $Header.= PHP_EOL.'User: '.$Call['Session']['User']['ID']
                .(isset($Call['Session']['User']['Title'])? '('.$Call['Session']['User']['Title'].')': '');

        if (is_array($Call['Value']))
        {
            $OutputLog = '<table class="console"><thead><tr class="'.$Call['Levels'][11].'">
<td colspan="4" style="border-left: 10px solid #ffffff;">'.$Header.'</td></tr></thead>';

            $LastTS = 0;
            foreach ($Call['Value'] as $IX => $Row)
            {
                if (is_scalar($Row[2]))
                    $Row[2] = stripslashes(htmlentities($Row[2]));
                else
                    $Row[2] = '<pre><code class="json">'.j($Row[2],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'</code></pre>';
                    
                $OutputLog .= '<tr class="'.$Call['Levels'][$Row[0]].'" style="border-left: 10px solid #'.$Row[6].';">
                        <td>['.$Row[0].'] '.$Row[1].' (+'.round($Row[1]-$LastTS, 3).')</td>
                        <td>'.($Row[3] == (isset($Call['Value'][$IX-1][3])? $Call['Value'][$IX-1][3]: false)? '': $Row[3]).'</td>
                        <td>'.$Row[2].'</td>
                        </tr>';
                if (isset($Row[5]))
                    $OutputLog .= '<tr class="'.$Call['Levels'][$Row[0]].'">
                        <td></td>
                        <td></td>
                        <td>'.$Row[5].'</td>
                        </tr>';
                $LastTS = $Row[1];
            }
            $OutputLog .= '</table>';
        }
        else
            $OutputLog = $Header.PHP_EOL.$Call['Value'];

        $OutputLog = preg_replace('/\*(.*)\*/SsUu', '<strong class="strong">$1</strong>', $OutputLog);

        $Output = file_get_contents(Codeine.'/Assets/Formats/Log/HTML.html');
        
        $Output = str_replace('<output:logs/>', $OutputLog, $Output);
        
        return $Output;
    });