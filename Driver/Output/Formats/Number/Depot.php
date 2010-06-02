<?php

    function F_Depot_Format ($Number)
    {
       if ($Number>1000)
           $Output = '<l>Format:Number:Depot:Many</l>';
       elseif ($Number>100)
           $Output = '<l>Format:Number:Depot:Enough</l>';
       elseif ($Number>50)
           $Output = '<l>Format:Number:Depot:Upper50</l>';
       elseif ($Number>10)
           $Output = '<l>Format:Number:Depot:Upper10</l>';
       elseif ($Number>3)
           $Output = $Number;
       elseif ($Number == 2)
           $Output = '<l>Format:Number:Depot:Twin</l>';
       elseif ($Number == 1)
           $Output = '<l>Format:Number:Depot:Last</l>';
       elseif ($Number == 0)
           $Output = '<l>Format:Number:Depot:Empty</l>';

       return $Output;
    }
    
    