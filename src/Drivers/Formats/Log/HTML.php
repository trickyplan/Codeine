<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if (F::Dot($Call, 'View.Renderer.Service') == 'View.HTML' && empty($Call['Context']))
            ;
        else
            return null;

        
            
        $CSS = F::Run('IO', 'Read', [
            'Storage'   => 'CSS',
            'Scope'     => 'Default/css',
            'Where'     => 'Log',
            'IO One'    => true
        ]);
        
        $OutputLog = '<style type="text/css">'.$CSS.'</style>';
        
        /*
            0 $Verbose,
            1 $Time,
            2 $Hash,
            3 $Message,
            4 $From,
            5 $StackDepth,
            6 F::Stack(),
            7 self::getColor()
        */
        if (is_array($Call['Value']))
        {
            $OutputLog .= '<table class="codeine-log" style="width: 100%;">';

            foreach ($Call['Value'] as $IX => $Row)
            {
                if (is_scalar($Row[3]))
                    $Row[3] = stripslashes(htmlentities($Row[3]));
                else
                    $Row[3] = '<pre><code class="json">'.wordwrap(j($Row[3],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 80).'</code></pre>';
                    
                $OutputLog .= '<tr class="'.$Call['Levels'][$Row[0]].'" style="border-left-color: #'.$Row[7].';">
                        <td>'.$Row[1].'</td>
                        <td>'.($Row[4] == (isset($Call['Value'][$IX-1][4])? $Call['Value'][$IX-1][4]: false)? '': $Row[4]).'</td>
                        <td>'.$Row[2].'</td>
                        <td>'.$Row[3].'</td>
                        </tr>';
                if (isset($Row[6]))
                    $OutputLog .= '<tr class="'.$Call['Levels'][$Row[0]].'">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>'.$Row[6].'</td>
                        </tr>';
            }
            $OutputLog .= '</table>';
        }
        else
            $OutputLog = $Call['Value'];

        if (F::Dot($Call, 'Log.Asterisk'))
            $OutputLog = preg_replace('/\*(.*)\*/SsUu', '<strong class="strong">$1</strong>', $OutputLog);

        $Output = file_get_contents(Codeine.'/Assets/Formats/Log/HTML.html');
        $Output = str_replace('<output:logs/>', $OutputLog, $Output);
        
        return $Output;
    });