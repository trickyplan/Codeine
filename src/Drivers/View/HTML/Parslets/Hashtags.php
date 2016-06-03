<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$IX].'></root>');

            if ($Root->attributes()->href !== null)
                $Href = (string) $Root->attributes()->href;
            else
                $Href = '/search/';

            if (preg_match_all('/#([\w\.\+\-\#_]+)/', $Match, $Hashtags))
            {
                foreach ($Hashtags[1] as $HashIndex => $Hashtag)
                    $Call['Parsed'][2][$IX] =
                        str_replace($Hashtags[0][$HashIndex],
                                    '<a class="hashtag" href="'.$Href.$Hashtag.'">'.$Hashtag.'</a>',
                                    $Call['Parsed'][2][$IX]);
            }
        }

        $Call['Output'] = str_replace($Call['Parsed'][0][$IX], $Call['Parsed'][2][$IX], $Call['Output']);
        return $Call;
    });