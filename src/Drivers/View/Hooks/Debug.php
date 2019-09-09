<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['View']['Debug']) && $Call['View']['Debug'] && !isset($Call['No View Debug']))
        {
            $ID = $Call['Scope'].':'.$Call['ID'].(isset($Call['Context'])? ':'.$Call['Context']: '');
            /*if (empty($Call['Value']))
                $Call['Value'] = '<!-- '.$ID.' not found --><place>Content</place>';
            else*/
            $Call['Value'] = '<!-- '.$ID.' started -->'.$Call['Value'].'<!-- '.$ID.' ended -->';
        }

        return $Call;
    });