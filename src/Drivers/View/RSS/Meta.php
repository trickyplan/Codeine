<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        $Call = F::Apply(null, 'Title', $Call);
        $Call = F::Apply(null, 'Keywords', $Call);
        $Call = F::Apply(null, 'Description', $Call);
        $Call = F::Apply(null, 'Header', $Call);

        return $Call;
    });

    setFn('Title', function ($Call)
    {
        $Call = F::Live($Call['Meta']['Sources']['Title'], $Call);

        if (isset($Call['Title'][count($Call['Title'])-1]))
            $Call['Header'] = $Call['Title'][count($Call['Title'])-1];

        if ($Call['Meta']['Title']['Reverse'])
            $Call['Title'] = array_reverse($Call['Title']);

        $Call['Title'] = implode($Call['Meta']['Title']['Delimiter'], $Call['Title']);

        return $Call;
    });

    setFn('Keywords', function ($Call)
    {
        $Call = F::Live($Call['Meta']['Sources']['Keywords'], $Call);

        if (!isset($Call['Keywords']))
            $Call['Keywords'] = '';
        else
            if (is_array($Call['Keywords']))
                $Call['Keywords'] = implode(',', $Call['Keywords']);

        return $Call;
    });

    setFn('Description', function ($Call)
    {
        $Call = F::Live($Call['Meta']['Sources']['Description'], $Call);

        if (!isset($Call['Description']))
            $Call['Description'] = '';

        if (is_array($Call['Description']))
            $Call['Description'] = implode('', $Call['Description']);

        return $Call;
    });

    setFn('Header', function ($Call)
    {
        if (isset($Call['Header']))
            $Call['Output'] = str_replace('<header/>', $Call['Header'], $Call['Output']);
        else
            $Call['Output'] = str_replace('<header/>', '', $Call['Output']);

        return $Call;
    });