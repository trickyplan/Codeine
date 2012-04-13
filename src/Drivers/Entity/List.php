<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
       // $Call['URL'] = '/reviews/'.strtolower($Call['Entity']).'/'.$Call['Where'][$Call['Entity']];

       // $Call['EPP'] = 5;

        if (!isset($Call['Page']))
            $Call['Page'] = 1;

        /*
         * Всю эту хуйню надо оформить как before/after хуки
         */
       // $Call['Front']['Count'] = F::Run('Entity', 'Count', $Call);
       // $Call['Limit']['From']= ($Call['Page']-1)*$Call['EPP'];
       // $Call['Limit']['To'] = $Call['EPP'];

       // $Call['PageCount'] = ceil($Call['Front']['Count']/$Call['EPP']);

        $Elements = F::Run('Entity', 'Read', $Call);

        if (sizeof($Elements) == 0)
            $Call['Output']['Content'][] = array(
                'Type'  => 'Template',
                'Scope' => $Call['Entity'],
                'Value' => 'Empty'
            );
        else
        {
            foreach ($Elements as $Element)
                $Call['Output']['Content'][] = array(
                    'Type'  => 'Template',
                    'Scope' => $Call['Entity'],
                    'Value' => 'Show.'.(isset($Call['Template'])? $Call['Template']: 'Short'),
                    'Data' => $Element
                );

            /* $Call['Output']['Content'][] = array(
                'Type'  => 'Paginator',
                'Total' => $Call['Front']['Count'],
                'EPP' => $Call['EPP'],
                'Page' => $Call['Page'],
                'URL' => $Call['URL'],
                'PageCount' => $Call['PageCount']
            ); */
        }



        return $Call;
    });