<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Enumerate Files', function ($Call)
    {
        $Paths = F::getPaths();
        $Files = [];
        
        foreach ($Paths as $Path)
        {
            if (file_exists($Path.'/Options/Project.json'))
            {
                $Project = jd(file_get_contents($Path.'/Options/Project.json'));
                $Project = $Project['ID'];
            }
            else
                $Project = $Path;
            
            $Path .= DS.$Call['Type'];
            $Files[$Project] = [];
            if (is_dir($Path))
            {
                $Directory = new RecursiveDirectoryIterator($Path);
                $Iterator = new RecursiveIteratorIterator($Directory);
                $Regex = new RegexIterator($Iterator,
                    '@'.$Call['Type'].'/(.+)'.$Call['Extension'].'$@', RecursiveRegexIterator::GET_MATCH);
    
                foreach ($Regex as $File)
                    $Files[$Project][] = strtr($File[1], DS, '.');
            }
        }
        
        foreach ($Files as $Project => &$cFiles)
            sort($cFiles);
        
        return $Files;
    });