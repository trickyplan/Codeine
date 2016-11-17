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
            $Call = F::Apply('Error.Page', 'Do', $Call, ['Code' => 404]);

        return $Call;
    });