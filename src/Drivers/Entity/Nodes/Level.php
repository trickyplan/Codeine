<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $ParentID = F::Dot($Call['Data'], 'Parent');

        if ($ParentID == $Call['Data']['ID'])
            $Level = 0;
        elseif ($ParentID === null)
            $Level = 0;
        else
        {
            $Parent = F::Run('Entity', 'Read', $Call, ['Where' => $ParentID, 'One' => true]);

            if (isset($Parent['Level']))
                $Level = $Parent['Level']+1;
            else
                $Level = 1;
        }

        return $Level;
    });