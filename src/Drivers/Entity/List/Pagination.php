<?php

    /* Codeine
     * @author BreathLess
     * @description Pagination hooks 
     * @package Codeine
     * @version 7.x
     */

    setFn('beforeList', function ($Call)
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

    setFn('afterList', function ($Call)
    {
        if (isset($Call['NoPage']) && ($Call['NoPage'] == true))
            return $Call;

        if ((isset($Call['PageCount']) && $Call['PageCount']>1) && !isset($Call['Count']))
            $Call['Output']['Pagination'][] = array(
                'Type'  => 'Paginator',
                'Total' => $Call['Front']['Count'],
                'EPP' => $Call['EPP'],
                'Page' => $Call['Page'],
                'FirstURL' => isset($Call['PageURL'])? str_replace($Call['PageURL'], '', $_SERVER['REQUEST_URI']).(isset($Call['PageURLPostfix'])? $Call['PageURLPostfix']: ''): '',
                'PageURL' => isset($Call['PageURL'])? $Call['PageURL']: '',
                'PageCount' => $Call['PageCount'],
                'PageURLPostfix' => isset($Call['PageURLPostfix'])? $Call['PageURLPostfix']: ''
            );

        return $Call;
    });