<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Scan', function ($Call)
    {
        if (isset($Call['URI']))
        {
            $Call['Title'] = [$Call['Project']['Title']];

            $Page = F::Run(null, 'Page', $Call);

            $Call = F::Live($Call['Meta Sources']['Title'], $Call);
            $Call = F::Live($Call['Meta Sources']['Keywords'], $Call);
            $Call = F::Live($Call['Meta Sources']['Description'], $Call);

            if (!empty($Page))
                $Call = F::Merge($Call, $Page);

            if ($Call['Reverse'])
                $Call['Title'] = array_reverse($Call['Title']);

            $Call['Title'] = implode($Call['Delimiter'], $Call['Title']);

            if (!isset($Call['Keywords']))
                $Call['Keywords'] = '';

            if (!isset($Call['Description']))
                $Call['Description'] = '';

            if (is_array($Call['Keywords']))
                $Call['Keywords'] = implode('', $Call['Keywords']);

            $Call['Output'] = str_replace('<title/>', '<title>'.$Call['Title'].'</title>', $Call['Output']);
            $Call['Output'] = str_replace('<keywords/>', '<meta name="keywords" content="'.$Call['Keywords'].'" />', $Call['Output']);
            $Call['Output'] = str_replace('<description/>', '<meta name="description" content="'.$Call['Description'].'" />', $Call['Output']);
        }

        return $Call;
    });

    setFn('Page', function ($Call)
    {
        $Page = F::Run('Entity', 'Read',
                    [
                         'Entity' => 'Page',
                         'Where' => ['Slug' => substr($Call['URI'], 1)]
                    ]);

        $Data = [];

        if (isset($Page[0]))
        {
            $Data['Title'][1] = $Page[0]['Title'];
            $Data['Description'] = $Page[0]['Description'];
            $Data['Keywords'] = $Page[0]['Keywords'];
        }

        return $Data;
    });