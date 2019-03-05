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
        
        if (is_array($Call['Value']))
        {
            $OutputLog .= '<table class="codeine-log" style="width: 100%;">';

            $LastTS = 0;
            foreach ($Call['Value'] as $IX => $Row)
            {
                if (is_scalar($Row[2]))
                    $Row[2] = stripslashes(htmlentities($Row[2]));
                else
                    $Row[2] = '<pre><code class="json">'.wordwrap(j($Row[2],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 80).'</code></pre>';
                    
                $OutputLog .= '<tr class="'.$Call['Levels'][$Row[0]].'" style="border-left-color: #'.$Row[6].';">
                        <td>'.$Row[1].'</td>
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
            $OutputLog = $Call['Value'];

        if (F::Dot($Call, 'Log.Asterisk'))
            $OutputLog = preg_replace('/\*(.*)\*/SsUu', '<strong class="strong">$1</strong>', $OutputLog);

        $Output = file_get_contents(Codeine.'/Assets/Formats/Log/HTML.html');
        $Output = str_replace('<output:logs/>', $OutputLog, $Output);
        
        return $Output;
    });