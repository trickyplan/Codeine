<?php

    /* Codeine
     * @author BreathLess
     * @description Pagination hooks 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('beforeList', function ($Call)
    {
        if (isset($Call['NoPage']) && ($Call['NoPage'] == true))
            return $Call;

        if (!isset($Call['Count']))
        {
            if (!isset($Call['Page']))
                $Call['Page'] = 1;

            $Call['Front']['Count'] = F::Run('Entity', 'Count', $Call);
            $Call['Limit']['From']= ($Call['Page']-1)*$Call['EPP'];
            $Call['Limit']['To'] = $Call['EPP'];

            $Call['PageCount'] = ceil($Call['Front']['Count']/$Call['EPP']);
        }
        else
        {
            $Call['Limit']['From']= 0;
            $Call['Limit']['To'] = $Call['Count'];
        }

        return $Call;
    });

    self::setFn('afterList', function ($Call)
    {
        if (isset($Call['NoPage']) && ($Call['NoPage'] == true))
            return $Call;

        if (($Call['PageCount']>1) && !isset($Call['Count']))
            $Call['Output']['Pagination'][] = array(
                'Type'  => 'Paginator',
                'Total' => $Call['Front']['Count'],
                'EPP' => $Call['EPP'],
                'Page' => $Call['Page'],
                'PageURL' => $Call['PageURL'],
                'PageCount' => $Call['PageCount']
            );

        return $Call;
    });