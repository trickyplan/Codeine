<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Replaces[$IX] = $Match;
            
            if (F::Dot($Call['Parsed'],'Options.'.$IX.'.href') !== null)
                $Href = F::Dot($Call['Parsed'],'Options.'.$IX.'.href');
            else
                $Href = '/search/';

            if (F::Dot($Call['Parsed'],'Options.'.$IX.'.css') !== null)
                $CSS = F::Dot($Call['Parsed'],'Options.'.$IX.'.css');
            
            if (preg_match_all('/#([\w\.\+\-\#_\']+)/', $Match, $Hashtags))
            {
                if (F::Dot($Call['Parsed'],'Options.'.$IX.'.nohref'))
                {
                    if (isset($CSS))
                        foreach ($Hashtags[1] as $HashIndex => $Hashtag)
                            $Replaces[$IX] =
                                str_replace($Hashtags[0][$HashIndex],
                                    '<span class="'.$CSS.'">'.$Hashtag.'</span>',
                                    $Replaces[$IX]);
                    else
                        foreach ($Hashtags[1] as $HashIndex => $Hashtag)
                            $Replaces[$IX] =
                                str_replace($Hashtags[0][$HashIndex],
                                    $Hashtag,
                                    $Replaces[$IX]);
                }
                else
                    foreach ($Hashtags[1] as $HashIndex => $Hashtag)
                        $Replaces[$IX] =
                            str_replace($Hashtags[0][$HashIndex],
                                        '<a class="hashtag" href="'.$Href.urlencode($Hashtag).'">'.$Hashtag.'</a>',
                                        $Replaces[$IX]);
            }
        }
        
        return $Replaces;
    });