<?php

  function F_Table_Output($Args)
  {
        foreach($Args as $K => $C)
            $PerfStr[$K] = '<tr style="font-size: '.round($C['C']/30,2).'em;">
                <td>'.$C['S'].'</td>
                <td>'.round($C['T']*1000,2).' ms</td>
                <td>('.$C['C'].')</td>
                </tr>';

        echo "<table width=100% class='Block Perfomance_Table' border=0><caption class='Perfomance_Caption'>Отчёт по таймерам</caption>".implode('', $PerfStr).'</table>';
  }