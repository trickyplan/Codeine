<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Pagination hooks 
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeList', function ($Call)
    {
        if (isset($Call['Random']))
        {
            $Call['Elements'] = F::Run('Entity', 'Read', $Call);

            if (empty($Call['Elements']))
                ;
            else
                shuffle($Call['Elements']);
        }

        return $Call;
    });