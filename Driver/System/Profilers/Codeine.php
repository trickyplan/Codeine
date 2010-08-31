<?php

  function F_Codeine_Profile($Args)
  {
        foreach($Args as $K => $C)
            $PerfStr[$K] = '<tr>
                <td>'.$C['S'].'</td>
                <td align= right>'.round($C['T']*1000,2).' ms</td>
                <td align=right>('.$C['C'].')</td>
                </tr>';

        return "<table class='Block' border=0 cellpadding=4 ><caption class='Perfomance_Caption'>Отчёт по таймерам</caption>".implode('', $PerfStr).'</table>';
  }