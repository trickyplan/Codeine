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

        if (isset($Call['Page']['Title'][count($Call['Page']['Title'])-1]))
            $Call['Page']['Header'] = $Call['Page']['Title'][count($Call['Page']['Title'])-1];

        if ($Call['Meta']['Title']['Reverse'])
            $Call['Page']['Title'] = array_reverse($Call['Page']['Title']);

        $Call['Page']['Title'] = implode($Call['Meta']['Title']['Delimiter'], $Call['Page']['Title']);

        return $Call;
    });

    setFn('Keywords', function ($Call)
    {
        $Call = F::Live($Call['Meta']['Sources']['Keywords'], $Call);

        if (!isset($Call['Page']['Keywords']))
            $Call['Page']['Keywords'] = '';
        else
            if (is_array($Call['Page']['Keywords']))
                $Call['Page']['Keywords'] = implode(',', $Call['Page']['Keywords']);

        return $Call;
    });

    setFn('Description', function ($Call)
    {
        $Call = F::Live($Call['Meta']['Sources']['Description'], $Call);

        if (!isset($Call['Page']['Description']))
            $Call['Page']['Description'] = '';

        if (is_array($Call['Page']['Description']))
            $Call['Page']['Description'] = implode('', $Call['Page']['Description']);

        return $Call;
    });

    setFn('Header', function ($Call)
    {
        if (isset($Call['Page']['Header']))
            $Call['Output'] = str_replace('<header/>', $Call['Page']['Header'], $Call['Output']);
        else
            $Call['Output'] = str_replace('<header/>', '', $Call['Output']);

        return $Call;
    });