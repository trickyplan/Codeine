<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed']['Options'][$IX].'></root>');

            if ($Root->attributes()->href !== null)
                $Href = (string) $Root->attributes()->href;
            else
                $Href = '/search/';

            if ($Root->attributes()->css !== null)
                $CSS = (string) $Root->attributes()->css;
                
            if (preg_match_all('/#([\w\.\+\-\#_\']+)/', $Match, $Hashtags))
            {
                if (isset($Root->attributes()->nohref))
                {
                    if (isset($CSS))
                        foreach ($Hashtags[1] as $HashIndex => $Hashtag)
                            $Call['Parsed']['Value'][$IX] =
                                str_replace($Hashtags[0][$HashIndex],
                                    '<span class="'.$CSS.'">'.$Hashtag.'</span>',
                                    $Call['Parsed']['Value'][$IX]);
                    else
                        foreach ($Hashtags[1] as $HashIndex => $Hashtag)
                            $Call['Parsed']['Value'][$IX] =
                                str_replace($Hashtags[0][$HashIndex],
                                    $Hashtag,
                                    $Call['Parsed']['Value'][$IX]);
                }
                else
                    foreach ($Hashtags[1] as $HashIndex => $Hashtag)
                        $Call['Parsed']['Value'][$IX] =
                            str_replace($Hashtags[0][$HashIndex],
                                        '<a class="hashtag" href="'.$Href.$Hashtag.'">'.$Hashtag.'</a>',
                                        $Call['Parsed']['Value'][$IX]);
            }
        }

        $Call['Output'] = str_replace($Call['Parsed']['Match'], $Call['Parsed']['Value'], $Call['Output']);
        
        return $Call;
    });