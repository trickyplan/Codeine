<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get Ancestors', function ($Call)
    {
        $ParentKey = $Call['Parent Key'];
        $Current = $Call['Data'];
        $Ancestors = [];
        do
        {
            $ParentID = F::Dot($Current, $ParentKey);
            if ($ParentID == $Current['ID'])
                break;
            
            if ($ParentID === null)
                break;
            
            $Ancestors[] = $ParentID;
            $Current = F::Run('Entity', 'Read', $Call, ['Where' => $ParentID, 'One' => true]);
        }
        while (true);
        
        $Ancestors = array_reverse($Ancestors);
        return $Ancestors;
    });