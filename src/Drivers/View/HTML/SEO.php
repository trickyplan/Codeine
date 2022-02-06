<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 43.6.1
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['HTTP']['URI']))
        {
            $Call = F::Apply(null, 'Title', $Call);
            $Call = F::Apply(null, 'Keywords', $Call);
            $Call = F::Apply(null, 'Description', $Call);
        }

        return $Call;
    });

    setFn('Title', function ($Call)
    {
        if (empty($Call['SEO']['Titles']))
            ;
        else
        {
            $Call = F::Dot($Call, 'View.HTML.SEO.Output.Titles', array_pop($Call['SEO']['Titles']));
            $Title = F::Dot($Call, 'View.HTML.SEO.Output.Titles');
            $SZ = mb_strlen($Title);

            if (F::Dot($Call, 'View.HTML.SEO.Titles.Size.Max')
                && $SZ > F::Dot($Call, 'View.HTML.SEO.Titles.Size.Max'))
                F::Log('Title *'.$Title.'* is too long', LOG_NOTICE, 'Marketing');

            if (F::Dot($Call, 'View.HTML.SEO.Titles.Size.Min')
                && $SZ < F::Dot($Call, 'View.HTML.SEO.Titles.Size.Min'))
                F::Log('Title *'.$Title.'* is too short', LOG_NOTICE, 'Marketing');

            $VCall = F::Apply('View.HTML.Widget.Base', 'Make', $Call,
            [
                'Type'  => 'Base',
                'Tag'  => 'title',
                'Value' => $Call['View']['HTML']['SEO']['Output']['Titles']
            ]);

            $Call['Output'] = str_replace(
                    F::Dot($Call, 'View.HTML.SEO.Titles.Pattern'),
                    $VCall['HTML'],
                    $Call['Output']);
        }

        return $Call;
    });

    setFn('Keywords', function ($Call)
    {
        if (empty($Call['SEO']['Keywords']))
            ;
        else
        {
            if (F::Dot($Call, 'View.HTML.SEO.Keywords.MakeUnique'))
                $Call['SEO']['Keywords'] = array_unique($Call['SEO']['Keywords']);
            if (F::Dot($Call, 'View.HTML.SEO.Keywords.Sort'))
                sort($Call['SEO']['Keywords'], SORT_ASC);

            $Call['View']['HTML']['SEO']['Output']['Keywords'] = implode(
                F::Dot($Call, 'View.HTML.SEO.Keywords.Separator'), $Call['SEO']['Keywords']);
                // strip_tags($Call['View']['HTML']['Keywords'])

            $VCall = F::Apply('View.HTML.Widget.Base', 'Make', $Call,
            [
                'Type'  => 'Base',
                'Tag'  => 'meta',
                'Attributes' =>
                [
                    'String' =>
                    [
                        'name'      => 'keywords',
                        'content'   => $Call['View']['HTML']['SEO']['Output']['Keywords']
                    ]
                ],
                'Value' => null
            ]);

            $Call['Output'] = str_replace(
                    F::Dot($Call, 'View.HTML.SEO.Keywords.Pattern'),
                    $VCall['HTML'],
                    $Call['Output']);
        }

        return $Call;
    });

    setFn('Description', function ($Call)
    {
        if (empty($Call['SEO']['Descriptions']))
            ;
        else
        {
            if (F::Dot($Call, 'View.HTML.SEO.Descriptions.MakeUnique'))
                $Call['SEO']['Descriptions'] = array_unique($Call['SEO']['Descriptions']);
            if (F::Dot($Call, 'View.HTML.SEO.Descriptions.Sort'))
                sort($Call['SEO']['Descriptions'], SORT_ASC);

            $Call = F::Dot($Call, 'View.HTML.SEO.Output.Descriptions',
                implode(
                    F::Dot($Call, 'View.HTML.SEO.Description.Separator'), $Call['SEO']['Descriptions']));

            $Description = F::Dot($Call, 'View.HTML.SEO.Output.Descriptions');
            $SZ = mb_strlen($Description);

            if (F::Dot($Call, 'View.HTML.SEO.Description.Size.Max')
                && $SZ > F::Dot($Call, 'View.HTML.SEO.Description.Size.Max'))
                F::Log('Description *'.$Description.'* is too long', LOG_NOTICE, 'Marketing');

            if (F::Dot($Call, 'View.HTML.SEO.Description.Size.Min')
                && $SZ < F::Dot($Call, 'View.HTML.SEO.Description.Size.Min'))
                F::Log('Description *'.$Description.'* is too short', LOG_NOTICE, 'Marketing');

            $VCall = F::Apply('View.HTML.Widget.Base', 'Make', $Call,
            [
                'Type'  => 'Base',
                'Tag'  => 'meta',
                'Attributes' =>
                [
                    'String' =>
                    [
                        'name'      => 'description',
                        'content'   => $Call['View']['HTML']['SEO']['Output']['Descriptions']
                    ]
                ],
                'Value' => null
            ]);

            $Call['Output'] = str_replace(
                    F::Dot($Call, 'View.HTML.SEO.Descriptions.Pattern'),
                    $VCall['HTML'],
                    $Call['Output']);
        }
        return $Call;
    });