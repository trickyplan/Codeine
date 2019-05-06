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

            $LastRow = ['M' => 0];

            foreach ($Call['Value'] as $IX => $Row)
            {
                if ($Row['Z'])
                    $Row['X'] = stripslashes(htmlentities($Row['X']));
                else
                    $Row['X'] = '<pre><code class="json">'.wordwrap($Row['X'], 80).'</code></pre>';

                $Row['M'] = round($Row['M']/1024);
                $MemoryDiff = $Row['M'] - $LastRow['M'];

                if ($MemoryDiff > 0)
                    $MemoryDiff = '+'.$MemoryDiff;

                $OutputLog .=
                    '<tr class="'.$Call['Levels'][ceil($Row['V'])].'" style="border-left-color: #'.$Row['C'].';">
                        <td>'.$Row['T'].'</td>
                        <td>'.$Row['M'].' ('.$MemoryDiff.')'.'</td>
                        <td>'.($Row['R'] == (isset($Call['Value'][$IX-1]['R'])? $Call['Value'][$IX-1]['R']: false)? '': $Row['R'].' from '.$Row['I']).'</td>
                        <td>'.$Row['H'].'</td>
                        <td>'.$Row['X'].'</td>
                        </tr>';
                if (isset($Row['K']))
                    $OutputLog .= '<tr class="'.$Call['Levels'][$Row['V']].'">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>'.$Row['K'].'</td>
                        </tr>';

                $LastRow = $Row;
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