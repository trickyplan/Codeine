<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */
    
    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            if (preg_match('/(\+(\d+)) ?\(?(\d{3})\)? ?(\d{3})[ -]?(\d{2})[ -]?(\d{2})/', $Match, $Parts))
            {
                $TelForm = implode('', array_slice($Parts, 2, 6));
                $HumanForm = '+' . $Parts[2] . ' (' . $Parts[3] . ') ' . $Parts[4] . ' ' . $Parts[5] . ' ' . $Parts[6];
                $Replaces[$IX] = '<a class="tel" href="tel:' . $TelForm . '">' . $HumanForm . '</a>';
            } else
                $Replaces[$IX] = '<a class="tel" href="tel:' . $Match . '">' . $Match . '</a>';
        }
        
        return $Replaces;
    });