<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 43.6.1
     */

    setFn('Process', function ($Call) {
        if (isset($Call['HTTP']['URI'])) {
            $Call = F::Apply(null, 'Title', $Call);
            $Call = F::Apply(null, 'Keywords', $Call);
            $Call = F::Apply(null, 'Description', $Call);
        }

        return $Call;
    });

    setFn('Title', function ($Call) {
        if (empty($Call['SEO']['Titles'])) {
            $Replace = '';
        } else {
            $Call = F::Dot($Call, 'View.HTML.SEO.Output.Titles', array_pop($Call['SEO']['Titles']));
            $Title = trim(F::Dot($Call, 'View.HTML.SEO.Output.Titles'));
            $Title = strip_tags($Title);
            $SZ = mb_strlen($Title);

            if (F::Dot($Call, 'View.HTML.SEO.Titles.Size.Max')
                && $SZ > F::Dot($Call, 'View.HTML.SEO.Titles.Size.Max')) {
                F::Log('Title *' . $Title . '* is too long', LOG_NOTICE, 'Marketing');
            }

            if (F::Dot($Call, 'View.HTML.SEO.Titles.Size.Min')
                && $SZ < F::Dot($Call, 'View.HTML.SEO.Titles.Size.Min')) {
                F::Log('Title *' . $Title . '* is too short', LOG_NOTICE, 'Marketing');
            }

            $VCall = F::Apply(
                'View.HTML.Widget.Base',
                'Make',
                $Call,
                [
                    'Type' => 'Base',
                    'Tag' => 'title',
                    'Closing Tag' => true,
                    'Value' => $Title
                ]
            );

            $Replace = $VCall['HTML'];
        }

        $Call['Output'] = str_replace(
            F::Dot($Call, 'View.HTML.SEO.Titles.Pattern'),
            $Replace,
            $Call['Output']
        );

        return $Call;
    });

    setFn('Keywords', function ($Call) {
        if (empty($Call['SEO']['Keywords'])) {
            $Replace = '';
        } else {
            if (F::Dot($Call, 'View.HTML.SEO.Keywords.MakeUnique')) {
                $Call['SEO']['Keywords'] = array_unique($Call['SEO']['Keywords']);
            }
            if (F::Dot($Call, 'View.HTML.SEO.Keywords.Sort')) {
                sort($Call['SEO']['Keywords'], SORT_ASC);
            }

            foreach ($Call['SEO']['Keywords'] as &$Keyword) {
                $Keyword = trim($Keyword);
            }

            $Call['View']['HTML']['SEO']['Output']['Keywords'] = implode(
                F::Dot($Call, 'View.HTML.SEO.Keywords.Separator'),
                $Call['SEO']['Keywords']
            );
            // strip_tags($Call['View']['HTML']['Keywords'])

            $VCall = F::Apply(
                'View.HTML.Widget.Base',
                'Make',
                $Call,
                [
                    'Type' => 'Base',
                    'Tag' => 'meta',
                    'Attributes' =>
                        [
                            'String' =>
                                [
                                    'name' => 'keywords',
                                    'content' => $Call['View']['HTML']['SEO']['Output']['Keywords']
                                ]
                        ],
                    'Value' => null
                ]
            );

            $Replace = $VCall['HTML'];
        }

        $Call['Output'] = str_replace(
            F::Dot($Call, 'View.HTML.SEO.Keywords.Pattern'),
            $Replace,
            $Call['Output']
        );
        return $Call;
    });

    setFn('Description', function ($Call) {
        if (empty($Call['SEO']['Descriptions'])) {
            $Replace = '';
        } else {
            if (F::Dot($Call, 'View.HTML.SEO.Descriptions.MakeUnique')) {
                $Call['SEO']['Descriptions'] = array_unique($Call['SEO']['Descriptions']);
            }
            if (F::Dot($Call, 'View.HTML.SEO.Descriptions.Sort')) {
                sort($Call['SEO']['Descriptions'], SORT_ASC);
            }

            $Call = F::Dot(
                $Call,
                'View.HTML.SEO.Output.Descriptions',
                implode(
                    F::Dot($Call, 'View.HTML.SEO.Description.Separator'),
                    $Call['SEO']['Descriptions']
                )
            );

            $Description = trim(F::Dot($Call, 'View.HTML.SEO.Output.Descriptions'));
            $Description = strip_tags($Description);
            $SZ = mb_strlen($Description);

            if (F::Dot($Call, 'View.HTML.SEO.Description.Size.Max')
                && $SZ > F::Dot($Call, 'View.HTML.SEO.Description.Size.Max')) {
                F::Log('Description *' . $Description . '* is too long', LOG_NOTICE, 'Marketing');
            }

            if (F::Dot($Call, 'View.HTML.SEO.Description.Size.Min')
                && $SZ < F::Dot($Call, 'View.HTML.SEO.Description.Size.Min')) {
                F::Log('Description *' . $Description . '* is too short', LOG_NOTICE, 'Marketing');
            }

            $VCall = F::Apply(
                'View.HTML.Widget.Base',
                'Make',
                $Call,
                [
                    'Type' => 'Base',
                    'Tag' => 'meta',
                    'Attributes' =>
                        [
                            'String' =>
                                [
                                    'name' => 'description',
                                    'content' => $Description
                                ]
                        ],
                    'Value' => null
                ]
            );

            $Replace = $VCall['HTML'];
        }

        $Call['Output'] = str_replace(
            F::Dot($Call, 'View.HTML.SEO.Descriptions.Pattern'),
            $Replace,
            $Call['Output']
        );

        return $Call;
    });