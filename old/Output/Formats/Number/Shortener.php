<?php

    function F_Shortener_Format ($Number)
    {
       $Output = '';

       if ($Number > 1000000)
           $Output = round($Number/1000000, 2).' <l>S:Millions</l>';
       elseif ($Number > 1000)
           $Output = round($Number/1000).' <l>S:Thousands</l>';
       elseif ($Number > 100)
           $Output = round($Number/100).' <l>S:Hundreds</l>';
       else
           $Output = $Number;
       
       return $Output;
    }
    
    