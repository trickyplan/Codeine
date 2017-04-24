<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Path = Root.'/Features';
        
        if (is_dir($Path))
        {
            $Directory = new RecursiveDirectoryIterator($Path);
            $Iterator = new RecursiveIteratorIterator($Directory);
            $Regex = new RegexIterator($Iterator,
                '@(.+).json$@', RecursiveRegexIterator::GET_MATCH);

            foreach ($Regex as $Result)
                if (empty($Result[1]))
                    ;
                else
                    $Features[$Result[1]] = jd(file_get_contents($Result[0]));
        }
        
        ksort($Features);
        foreach ($Features as $Feature)
        {
            $Call['Output']['Content'][] =
                [
                    'Type'  => 'Template',
                    'Scope' => 'Developer/Feature',
                    'ID'    => 'Short',
                    'Data'  => $Feature
                ];
        }
        
        return $Call;
    });