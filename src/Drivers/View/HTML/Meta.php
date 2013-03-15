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

            $Call['Header'] = $Call['Title'][count($Call['Title'])-1];

            if ($Call['Reverse'])
                $Call['Title'] = array_reverse($Call['Title']);

            $Call['Title'] = implode($Call['Delimiter'], $Call['Title']);

            if (!isset($Call['Keywords']))
                $Call['Keywords'] = '';

            if (!isset($Call['Description']))
                $Call['Description'] = '';

            if (is_array($Call['Keywords']))
                $Call['Keywords'] = implode(',', $Call['Keywords']);

            if (is_array($Call['Description']))
                $Call['Description'] = implode('', $Call['Description']);

            $Call['Output'] = str_replace('<header/>', $Call['Header'], $Call['Output']);

            $Call['Output'] = str_replace('<title/>', '<title>'.strip_tags($Call['Title'], '<l><k>').'</title>', $Call['Output']);
            $Call['Output'] = str_replace('<keywords/>', '<meta name="keywords" content="'.strip_tags($Call['Keywords'], '<l><k>').'" />', $Call['Output']);
            $Call['Output'] = str_replace('<description/>', '<meta name="description" content="'.strip_tags($Call['Description'], '<l><k>').'" />', $Call['Output']);
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