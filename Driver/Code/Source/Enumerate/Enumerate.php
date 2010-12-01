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
        $Directory = new RecursiveDirectoryIterator(Engine.Data::Path('Code'));
        $Iterator = new RecursiveIteratorIterator($Directory);
        $Regex = new RegexIterator($Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        $List = array();
        
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