<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Current Image']['Lazy']) && $Call['Current Image']['Lazy'])
        {
            $Call['Current Image']['Widget']['data-original'] = $Call['Current Image']['Widget']['src'];
            $Call['Current Image']['Widget']['class'] .= ' lazy';
            unset($Call['Current Image']['Widget']['src']);
        }

        return $Call;
    });