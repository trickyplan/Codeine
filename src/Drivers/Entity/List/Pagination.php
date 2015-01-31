<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Pagination hooks 
     * @package Codeine
     * @version 8.x
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

            if (isset($Call['Sort']))
            {
                $ReducedLimit = round($Call['Limit']['From']/2);
                $MarkerCall =
                    [
                        'Entity' => $Call['Entity'],
                        'Sort'   => $Call['Sort'],
                        'Limit'  =>
                        [
                            'From' => $ReducedLimit,
                            'To'   => 1
                        ],
                        'One'      => true
                    ];

                if (isset($Call['Where']))
                    $MarkerCall['Where'] = $Call['Where'];

                $Marker = F::Run('Entity', 'Read', $MarkerCall);

                foreach ($Call['Sort'] as $Key => $Direction)
                    if (isset($Marker[$Key]))
                    {
                        if ($Direction) // ASC
                        {
                            $Call['Where'][$Key] = ['$gte' => $Marker[$Key]];
                        }
                        else // DESC
                        {
                            $Call['Where'][$Key] = ['$lte' => $Marker[$Key]];
                        }
                    }

                $Call['Limit']['From'] -= $ReducedLimit;
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