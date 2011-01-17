<?php

    function F_Age_Format ($Date)
    {
        $Years = (time()-$Date)/(60*60*24*365.25);
        $PreciseYear = number_format($Years,4);
        $Years = floor($Years);

        $LD = $Years%10;
        
        switch ($LD)
		{
			case '0': $Output = $Years.' <l>Date:Years</l>'; break;
			case '1': $Output = $Years.' <l>Date:Years:1</l>'; break;
			case '2': $Output = $Years.' <l>Date:Years:2</l>'; break;
			case '3': $Output = $Years.' <l>Date:Years:2</l>'; break;
                        case '4': $Output = $Years.' <l>Date:Years:2</l>'; break;
			default:  $Output = $Years.' <l>Date:Years</l>'; break;
		}

		if (($Years>10)&&($Years<20))
			$Output = $Years.' <l>Date:Years</l>';

        $Output.= ' <small>('.$PreciseYear.')</small>';
			
        return $Output;
    }
    
    