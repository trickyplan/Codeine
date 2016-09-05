<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if ($Call['Locale'] !== $Call['Default']['Locale'])
            if (F::Dot($Call, 'LocalizedURLs.Enabled') && preg_match_all('@a href="(/.*)"@SsUu',$Call['Output'], $Links))
            {
                $Matches = [];
                $Replaces = [];
                
                foreach ($Links[1] as $IX => $Link)
                {
                    $Localize = true;
    
                    if (in_array($Link, F::Dot($Call, 'LocalizedURLs.Excluded')))
                        $Localize = false;
    
                    if (preg_match('@^/'.$Call['Locale'].'/@', $Link))
                        $Localize = false;
                    
                    if ($Link == '/')
                        $Localize = false;
    
                    if ($Localize)
                    {
                        $Matches[] = $Link;
                        $Replaces[] = $Call['Locale'].$Link;
                    }
                }
                
                $Call['Output'] = str_replace($Matches, $Replaces, $Call['Output']);
            }

        return $Call;
    });