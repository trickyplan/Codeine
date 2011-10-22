<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Standart Driver Enumerator
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 22.11.10
     * @time 5:52
     */

    self::Fn('ListAll', function ($Call)
    {
        // FIXME Paths!
        if (!isset($Call['Namespace']))
            $Call['Namespace'] = '';
        else
            $Call['Namespace'] = strtr($Call['Namespace'], '.','/').'/';
        
        $Directory = new RecursiveDirectoryIterator(Codeine.'/Drivers/'.$Call['Namespace']);
        $Iterator = new RecursiveIteratorIterator($Directory);
        $Regex = new RegexIterator($Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        $List = array();

        if (!isset($Call['fullPaths']))
            $PathSize = strlen(Codeine.'/Driver/');
        else
            $PathSize = 0;
        
        if (isset($Call['onlyNames']))
            foreach ($Regex as $File)
            {
                $File = basename($File[0]);
                $List[] = substr($File, 0, strlen($File)-4);
            }
        else
            foreach ($Regex as $File)
                $List[] = mb_substr($File[0], $PathSize, strlen($File[0])-$PathSize-4);

        return $List;
    });

