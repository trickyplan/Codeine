<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            $Call['Replace'][$Call['Parsed']['Match'][$IX]] = $Match;

            if (F::Dot($Call['Parsed'], 'Options.' . $IX . '.href') !== null) {
                $Href = F::Dot($Call['Parsed'], 'Options.' . $IX . '.href');
            } else {
                $Href = '/search/';
            }

            if (F::Dot($Call['Parsed'], 'Options.' . $IX . '.css') !== null) {
                $CSS = F::Dot($Call['Parsed'], 'Options.' . $IX . '.css');
            }

            if (preg_match_all('/#([^\s]+)/', $Match, $Hashtags)) {
                if (F::Dot($Call['Parsed'], 'Options.' . $IX . '.nohref')) {
                    if (isset($CSS)) {
                        foreach ($Hashtags[1] as $HashIndex => $Hashtag) {
                            $Call['Replace'][$Call['Parsed']['Match'][$IX]] =
                                str_replace(
                                    $Hashtags[0][$HashIndex],
                                    '<span class="' . $CSS . '">' . $Hashtag . '</span>',
                                    $Call['Replace'][$Call['Parsed']['Match'][$IX]]
                                );
                        }
                    } else {
                        foreach ($Hashtags[1] as $HashIndex => $Hashtag) {
                            $Call['Replace'][$Call['Parsed']['Match'][$IX]] =
                                str_replace(
                                    $Hashtags[0][$HashIndex],
                                    $Hashtag,
                                    $Call['Replace'][$Call['Parsed']['Match'][$IX]]
                                );
                        }
                    }
                } else {
                    if (isset($CSS)) {
                        foreach ($Hashtags[1] as $HashIndex => $Hashtag) {
                            $Call['Replace'][$Call['Parsed']['Match'][$IX]] =
                                str_replace(
                                    $Hashtags[0][$HashIndex],
                                    '<a class="' . $CSS . '" href="' . $Href . urlencode(
                                        $Hashtag
                                    ) . '">' . $Hashtag . '</a>',
                                    $Call['Replace'][$Call['Parsed']['Match'][$IX]]
                                );
                        }
                    } else {
                        foreach ($Hashtags[1] as $HashIndex => $Hashtag) {
                            $Call['Replace'][$Call['Parsed']['Match'][$IX]] =
                                str_replace(
                                    $Hashtags[0][$HashIndex],
                                    '<a class="hashtag" href="' . $Href . urlencode(
                                        $Hashtag
                                    ) . '">' . $Hashtag . '</a>',
                                    $Call['Replace'][$Call['Parsed']['Match'][$IX]]
                                );
                        }
                    }
                }
            }
        }
        return $Call;
    });
