<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Do', function ($Call)
    {


        $DOT = 'digraph '.$Call['Title'].' { node [style=rounded]; node [shape=box];';

        if (isset($Call['Graph.Headers']))
            $DOT.= $Call['Graph.Headers'];
        foreach($Call['Value'] as $Link)
            $DOT.= $Link[0].'->'.$Link[1].';';

        $DOT .= '}';

        return shell_exec ('echo "'.$DOT.'" | '.$Call['Graphviz.Layout'].' -T'.$Call['Format']);
     });