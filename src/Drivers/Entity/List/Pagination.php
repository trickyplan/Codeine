<?php

    /* Codeine
     * @author BreathLess
     * @description Pagination hooks 
     * @package Codeine
     * @version 7.x
     */

    setFn('beforeList', function ($Call)
    {
        if (isset($Call['NoPage']) && $Call['NoPage'])
            return $Call;

        if (isset($Call['Limit']))
            ;
        else
        {
            if (!isset($Call['Count']) or empty($Call['Count']))
            {
                if (!isset($Call['Page']) or empty($Call['Page']))
                    $Call['Page'] = 1;

                $Call['Count'] = F::Run('Entity', 'Count', $Call);
                $Call['Limit']['From']= ($Call['Page']-1)*$Call['EPP'];
                $Call['Limit']['To'] = $Call['EPP'];

                $Call['PageCount'] = ceil($Call['Count']/$Call['EPP']);
            }
            else
            {
                $Call['Limit']['From']= 0;
                $Call['Limit']['To'] = $Call['Count'];
            }
        }
        return $Call;
    });

    setFn('afterList', function ($Call)
    {
        if (isset($Call['NoPage']) && $Call['NoPage'])
            return $Call;

        $Call['PageURLPostfix'] = isset($Call['PageURLPostfix'])? $Call['PageURLPostfix']: '';

        $Call['PageURLPostfix'].= isset($Call['HTTP']['URL Query'])? '?'.$Call['HTTP']['URL Query']: '';

        if (!isset($Call['FirstURL']) && isset($Call['HTTP']['URL']))
            $Call['FirstURL'] = preg_replace('@/page(\d+)@', '', $Call['HTTP']['URL']);


        if (isset($Call['PageCount']) && $Call['PageCount']>1)
            $Call['Output']['Pagination'][] =
            [
                'Type'  => 'Paginator',
                'Total' => $Call['Count'],
                'EPP' => $Call['EPP'],
                'Page' => $Call['Page'],
                'FirstURL' => isset($Call['FirstURL'])? $Call['FirstURL']: '',
                'PageURL' => isset($Call['PageURL'])? $Call['PageURL']: '',
                'PageCount' => $Call['PageCount'],
                'PageURLPostfix' => $Call['PageURLPostfix']
           ];

        return $Call;
    });