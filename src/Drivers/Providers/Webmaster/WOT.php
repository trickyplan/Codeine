<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Auth', function ($Call)
    {
        if ($Call['ID'] == $Call['WOT']['ID'])
            $Call['Output']['Content'][] = $Call['WOT']['Hash'];
        else
            $Call = F::Apply('Error.404', 'Page', $Call);

        return $Call;
    });