<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Where']))
        {
            if (isset($Call['Where']['ID']))
                $Call['Where']['ID'] = (array) $Call['Where']['ID'];
            else
                $Call['Where']['ID'] = [hash('sha512', j($Call['Where']))];
        }

        return $Call;
    });