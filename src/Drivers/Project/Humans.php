<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Project']['Team']))
        {
            $Call['Output']['Content'][] = '/* TEAM */';
            foreach ($Call['Project']['Team'] as $Role => $Humans)
                foreach ($Humans as $Name => $Human)
                {
                    $Call['Output']['Content'][] = $Role.': '.$Name;
                    foreach ($Human as $Key => $Value)
                        $Call['Output']['Content'][] = $Key.': '.$Value;
                    $Call['Output']['Content'][] = '';
                }
        }

        if (isset($Call['Project']['Thanks']))
        {
            $Call['Output']['Content'][] = '/* TEAM */';
            foreach ($Call['Project']['Thanks'] as $Role => $Humans)
                foreach ($Humans as $Name => $Human)
                {
                    $Call['Output']['Content'][] = $Role.': '.$Name;
                    foreach ($Human as $Key => $Value)
                        $Call['Output']['Content'][] = $Key.': '.$Value;
                    $Call['Output']['Content'][] = '';
                }
        }

        $Call['Output']['Content'][] = '/* SITE */';
        $Call['Output']['Content'][] = 'Last update: '.date('y/m/d');
        $Call['Output']['Content'][] = 'Doctype: HTML5';
        $Call['Output']['Content'][] = 'Engine: Codeine Framework';

        if (isset($Call['Project']['Languages']))
            $Call['Output']['Content'][] = 'Language: '.implode('/', $Call['Project']['Languages']);

        if (isset($Call['Project']['Tools']))
            $Call['Output']['Content'][] = 'Tools: '.implode('/', $Call['Project']['Tools']);

        return $Call;
    });