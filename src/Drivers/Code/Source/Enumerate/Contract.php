<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 6:51
     */

    self::Fn('ListAll', function ($Call)
    {
        // FIXME Paths
            $Directory = new RecursiveDirectoryIterator(Codeine.'/Options');
            $Iterator = new RecursiveIteratorIterator($Directory);
            $Regex = new RegexIterator($Iterator, '/^.+\.json$/i', RecursiveRegexIterator::GET_MATCH);

            $List = array();

            if (!isset($Call['fullPaths']))
                $PathSize = strlen(Codeine.'/Options');
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
                    $List[] = mb_substr($File[0], $PathSize, strlen($File[0])-$PathSize-5);

            return $List;
    });
