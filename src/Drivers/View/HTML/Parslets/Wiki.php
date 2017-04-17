<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Skype Parslet
     * @package Codeine
     * @version 6.0
     */
    
    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
            $Call['Output'] = str_replace($Call['Parsed']['Match'][$IX],
                '<img src="http://wikipedia.org/favicon.ico" class="icon"/> <a target="_blank" href="http://ru.wikipedia.org/wiki/' . $Match . '">' . $Match . '</a>', $Call['Output']);
        
        return $Replaces;
    });