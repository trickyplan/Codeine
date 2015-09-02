<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Scope']))
        {
            if (is_array($Call['Scope']))
                $Call['Path'] = implode(DS, $Call['Scope']);
            else
                $Call['Path'] = $Call['Scope'];
        }
        else
            $Call['Path'] = '';

        if (isset($Call['Where']['ID']))
            foreach ($Call['Where']['ID'] as &$ID)
                $ID = $Call['Path'].DS.$ID;

        return $Call;
    });