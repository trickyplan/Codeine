<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Where']['ID']))
            foreach ($Call['Where']['ID'] as &$ID)
                $ID = $Call['Prefix'].$ID;

        return $Call;
    });