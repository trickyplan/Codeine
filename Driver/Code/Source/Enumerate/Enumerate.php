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

    self::Fn('Drivers', function ($Call)
    {
        if (!isset($Call['Namespace']))
            $Call['Namespace'] = '';
        else
            $Call['Namespace'] = strtr($Call['Namespace'], '.',DS).DS;
        
        $Directory = new RecursiveDirectoryIterator(Engine.Data::Path('Code').$Call['Namespace']);
        $Iterator = new RecursiveIteratorIterator($Directory);
        $Regex = new RegexIterator($Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        $List = array();

        if (isset($Call['onlyNames']))
            foreach ($Regex as $File)
            {
                $File = basename($File[0]);
                $List[] = substr($File, 0, strlen($File)-4);
            }
        else
            foreach ($Regex as $File)
                $List[] = $File[0];

        return $List;
    });

    self::Fn('Contracts', function ($Call)
    {
        $Directory = new RecursiveDirectoryIterator(Engine.Data::Path('Code'));
        $Iterator = new RecursiveIteratorIterator($Directory);
        $Regex = new RegexIterator($Iterator, '/^.+\.json$/i', RecursiveRegexIterator::GET_MATCH);

        $List = array();

        foreach ($Regex as $File)
            $List[] = $File[0];

        return $List;
    });
