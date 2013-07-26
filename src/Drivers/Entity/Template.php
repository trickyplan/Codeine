<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Table', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        $Call['Renderer'] = 'View.Plaintext';

        foreach ($Call['Nodes'] as $Name => $Node)
            $Rows[] = '<block><tr><th><l>'.$Call['Entity'].'.Entity:'.$Name.'</l></th><td><k>'.$Name.'</k></td></tr></block>';

        $Call['Output']['Content'][] = '<table class="table">'.implode("\n", $Rows).'</table>';

        return $Call;
    });