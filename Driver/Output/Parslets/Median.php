<?php

    function F_Median_Parse ($Inputs)
    {
        if ($Inputs[1] == 0)
            $Ind = '000';
        
        if ($Inputs[1] > 0&&$Inputs[1]<100)
            $Ind = floor(10-$Inputs[1]/10).'0'.floor($Inputs[1]/10);
        
        if ($Inputs[1] >= 100)
            $Ind = '070';

        if ($Inputs[1] >= 110)
            $Ind = '090';

        $Output = '<span style="color: #'.$Ind.'">'.$Inputs[1].'</span>';
        
        return $Output;
    }
    
    