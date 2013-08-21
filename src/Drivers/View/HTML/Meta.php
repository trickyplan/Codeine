<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['URI']))
        {
            $Call = F::Run(null, 'Page', $Call);
            $Call = F::Run(null, 'Title', $Call);
            $Call = F::Run(null, 'Keywords', $Call);
            $Call = F::Run(null, 'Description', $Call);
            $Call = F::Run(null, 'Header', $Call);
        }

        return $Call;
    });

    setFn('Title', function ($Call)
    {
        if (preg_match($Call['Meta']['Pattern']['Title'], $Call['Output']))
        {
            $Call = F::Live($Call['Meta']['Sources']['Title'], $Call);

            if (isset($Call['Title'][count($Call['Title'])-1]))
                $Call['Header'] = $Call['Title'][count($Call['Title'])-1];

            if ($Call['Meta']['Title']['Reverse'])
                $Call['Title'] = array_reverse($Call['Title']);

            $Call['Title'] = implode($Call['Meta']['Title']['Delimiter'], $Call['Title']);

            $Call['Output'] = preg_replace(
                        $Call['Meta']['Pattern']['Title'],
                        '<title>'.strip_tags($Call['Title'], '<l><k>').'</title>',
                        $Call['Output']);
        }

        return $Call;
    });

    setFn('Keywords', function ($Call)
    {
        if (preg_match($Call['Meta']['Pattern']['Keywords'], $Call['Output']))
        {
            $Call = F::Live($Call['Meta']['Sources']['Keywords'], $Call);

            if (!isset($Call['Keywords']))
                $Call['Keywords'] = '';
            else
                if (is_array($Call['Keywords']))
                    $Call['Keywords'] = implode(',', $Call['Keywords']);

            $Call['Output'] = preg_replace(
                        $Call['Meta']['Pattern']['Keywords'],
                        '<meta name="keywords" content="'.strip_tags($Call['Keywords'], '<l><k>').'" />',
                        $Call['Output']);
        }

        return $Call;
    });

    setFn('Description', function ($Call)
    {
        if (preg_match($Call['Meta']['Pattern']['Description'], $Call['Output']))
        {
            $Call = F::Live($Call['Meta']['Sources']['Description'], $Call);

            if (!isset($Call['Description']))
                $Call['Description'] = '';

            if (is_array($Call['Description']))
                $Call['Description'] = implode('', $Call['Description']);

            $Call['Output'] = preg_replace(
                        $Call['Meta']['Pattern']['Description'],
                        '<meta name="description" content="'.strip_tags($Call['Description'], '<l><k>').'" />',
                        $Call['Output']);
        }

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

    setFn('Page', function ($Call)
    {
        if (
                preg_match($Call['Meta']['Pattern']['Title'], $Call['Output'])
                ||
                preg_match($Call['Meta']['Pattern']['Keywords'], $Call['Output'])
                ||
                preg_match($Call['Meta']['Pattern']['Description'], $Call['Output'])
        )
        {
            if ($Call['URL'] == '/')
                $Call['URL'] = '//';

            $Page = F::Run('Entity', 'Read',
                        [
                            'One' => true,
                            'Entity' => 'Page',
                            'Where' => ['Slug' => substr($Call['URL'], 1)]
                        ]);

            if ($Page !== null)
            {
                $Call['Title'][1] = $Page['Title'];
                $Call['Description'] = $Page['Description'];
                $Call['Keywords'] = $Page['Keywords'];
            }
        }

        return $Call;
    });