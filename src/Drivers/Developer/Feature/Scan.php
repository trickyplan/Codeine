<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Drivers = [];
        $Path = Root.'/Drivers';
        
        if (is_dir($Path))
        {
            $Directory = new RecursiveDirectoryIterator($Path);
            $Iterator = new RecursiveIteratorIterator($Directory);
            $Regex = new RegexIterator($Iterator,
                '@(.+).php$@', RecursiveRegexIterator::GET_MATCH);

            foreach ($Regex as $Result)
                $Drivers[] = str_replace($Path.DS, '', $Result[1]);
        }

        F::Log($Drivers, LOG_DEBUG);

        return $Drivers;
    });