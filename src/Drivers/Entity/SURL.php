<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        if (isset($Call['Data']['ID']))
            return DS.$Call['Slug']['Entity'].DS.$Call['Data']['ID'];
    });