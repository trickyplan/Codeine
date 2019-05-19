<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Pagination hooks 
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeList', function ($Call)
    {
        if (isset($Call['No Page']) && $Call['No Page'])
            return $Call;

        if (isset($Call['Limit']))
            ;
        else
        {
            if (isset($Call['Pagination']['ElementsPerPage']))
                ;
            else
                $Call['Pagination']['ElementsPerPage'] = $Call['EPP'];
            
            if (isset($Call['Count']) && !empty($Call['Count']))
            {
                $Call['Limit']['From']= 0;
                $Call['Limit']['To'] = $Call['Count'];
            }
            else
            {
                if (isset($Call['Elements']))
                    $Call['Pagination']['Count'] = count($Call['Elements']);
                else
                    $Call['Pagination']['Count'] = F::Run('Entity', 'Count', $Call);

                $Call['Pagination']['CountOfPages'] = ceil($Call['Pagination']['Count'] / $Call['Pagination']['ElementsPerPage']);
                F::Log('Count of elements: *'.$Call['Pagination']['Count'].'*', LOG_INFO);
                F::Log('Elements per page: *'.$Call['Pagination']['ElementsPerPage'].'*', LOG_INFO);
                F::Log('Count of pages: *'.$Call['Pagination']['CountOfPages'].'*', LOG_INFO);
                
                if (isset($Call['Page']))
                {
                    if (empty($Call['Page']))
                    {
                        F::Log('Page # is set but empty', LOG_INFO, 'Developer');
                        $Call['Page'] = 1;
                    }
                    else
                    {
                        if ($Call['Page'] > $Call['Pagination']['Limits']['CountOfPages'])
                        {
                            $Call['Page'] = $Call['Pagination']['Limits']['CountOfPages'];
                            F::Log('Page number (*'.$Call['Page'].'*) *capped* to *'.$Call['Pagination']['Limits']['CountOfPages'].'*', LOG_INFO, 'Performance');
                        }
                        
                        if ($Call['Page'] > $Call['Pagination']['CountOfPages'])
                        {
                            F::Log('Page number (*'.$Call['Page'].'*) is more than count of pages (*'.$Call['Pagination']['CountOfPages'].'*)', LOG_INFO, 'Performance');
                            $Call = F::Apply('System.Interface.HTTP', 'Redirect', $Call, 
                                [
                                    'Redirect' =>  preg_replace('@/page(\d+)@', '/page'.$Call['Pagination']['CountOfPages'], $Call['HTTP']['URL'])
                                ]);
                        }
                    }
                }
                else
                    $Call['Page'] = 1;
                
                $Call['Limit']['From'] = ($Call['Page']-1) * $Call['Pagination']['ElementsPerPage'];
                $Call['Limit']['To'] = $Call['Pagination']['ElementsPerPage'];
                $Call['Pagination']['Elements']['From'] = $Call['Limit']['From'];
                $Call['Pagination']['Elements']['To'] = $Call['Limit']['From'] + $Call['Limit']['To'];

                $Call['Pagination']['Labels']['From'] = $Call['Pagination']['Elements']['From']+1;
                $Call['Pagination']['Labels']['To'] = $Call['Pagination']['Elements']['To'];

                F::Log('Elements *'.$Call['Pagination']['Elements']['From'].'-'
                    .($Call['Pagination']['Elements']['To'] > $Call['Pagination']['Count']? $Call['Pagination']['Count']: $Call['Pagination']['Elements']['To'])
                    .'* selected', LOG_INFO);


                if ($Call['Pagination']['CountOfPages'] > 100)
                    $Call['Pagination']['CountOfPages'] = 100; // FIXME
            }

        }

        return $Call;
    });

    setFn('afterList', function ($Call)
    {
        if (isset($Call['No Page']) && $Call['No Page'])
            return $Call;

        $Call['PageURLPostfix'] = isset($Call['PageURLPostfix'])? $Call['PageURLPostfix']: '';

        $Call['PageURLPostfix'].= isset($Call['HTTP']['Query'])? '?'.$Call['HTTP']['Query']: '';

        if (!isset($Call['FirstURL']) && isset($Call['HTTP']['URL']))
            $Call['FirstURL'] = preg_replace('@/page(\d+)@', '', $Call['HTTP']['URL']);

        if (isset($Call['Pagination']['CountOfPages']) && $Call['Pagination']['CountOfPages']>1)
            $Call['Output']['Pagination'][] =
            [
                'Type'  => 'Paginator',
                'Total' => $Call['Pagination']['Count'],
                'Pagination' => // FIX Other fields
                [
                    'ElementsPerPage' => $Call['Pagination']['ElementsPerPage']
                ],
                'Page' => $Call['Page'],
                'FirstURL' => isset($Call['FirstURL'])? $Call['FirstURL']: '',
                'PageURL' => isset($Call['PageURL'])? $Call['PageURL']: '',
                'CountOfPages' => $Call['Pagination']['CountOfPages'],
                'PageURLPostfix' => $Call['PageURLPostfix'],
                'Locale'    => $Call['Locale']
           ];

        return $Call;
    });