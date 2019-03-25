<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Humans']['Team']))
        {
            $Call['Output']['Content'][] = '/* TEAM */';
            foreach ($Call['Humans']['Team'] as $Role => $Humans)
                foreach ($Humans as $Name => $Human)
                {
                    $Call['Output']['Content'][] = $Role.': '.$Name;
                    foreach ($Human as $Key => $Value)
                        $Call['Output']['Content'][] = $Key.': '.$Value;
                    $Call['Output']['Content'][] = '';
                }
        }

        if (isset($Call['Humans']['Thanks']))
        {
            $Call['Output']['Content'][] = '/* TEAM */';
            foreach ($Call['Humans']['Thanks'] as $Role => $Humans)
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

        if (isset($Call['Humans']['Languages']))
            $Call['Output']['Content'][] = 'Language: '.implode('/', $Call['Humans']['Languages']);

        if (isset($Call['Humans']['Tools']))
            $Call['Output']['Content'][] = 'Tools: '.implode('/', $Call['Humans']['Tools']);

        return $Call;
    });