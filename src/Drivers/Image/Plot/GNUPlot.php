<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $GNUPlot = popen('gnuplot', 'w');

        foreach ($Call['Data'] as &$Row)
            $Row = implode(' ', $Row);

        file_put_contents($Call['Data Filename'], implode(PHP_EOL, $Call['Data']));
        $Command = [];

        $Command[] = 'set terminal png size 1920,1080';
        $Command[] = 'set title "'.$Call['Plot']['Title'].'"';
        $Command[] = 'set timefmt "%d.%m.%y/%H:%M:%S"';
        $Command[] = 'set xlabel "Date / Time"';
        $Command[] = 'set xdata time';
/*        $Command[] = 'set yrange [ 0 : 100]';*/
        $Command[] = 'set ylabel "Value"';
        $Command[] = 'set mytics (10)';
        $Command[] = 'set mxtics (10)';
        $Command[] = 'set output "'.$Call['Image Filename'].'"';
        $Command[] = 'set grid xtics ytics';
        $Command[] = 'plot "'.$Call['Data Filename'].'" using 1:2 with filledcurve x1 ti "Количество"';
        $Command[] = 'quit';

        $Command = implode(PHP_EOL, $Command);
        fwrite($GNUPlot, $Command);
        pclose($GNUPlot);

        return $Call;
    });