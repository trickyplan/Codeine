<?php

    function F_Adaptive_Format ($Date)
    {
        $Now = time();
        $Output = '';

        $DateY = strftime("%Y",$Date);
        $DateM = strftime("%b",$Date);
        $DateD = strftime("%d",$Date);

        $NowY = strftime("%Y",$Now);
        $NowM = strftime("%b",$Now);
        $NowD = strftime("%d",$Now);

        $Output.= $DateD." ".$DateM;

        if ($NowY != $DateY)
            $Output.=  ' '.$DateY;
        
        if (($NowY == $DateY) && ($NowM == $DateM))
        {
                $DDiff = $NowD-$DateD;
                switch($DDiff)
                {
                    case 30: $Output = "<l>Date:Ago:Month</l>"; break;
                    case 21: $Output = "<l>Date:Ago:Week3</l>"; break;
                    case 14: $Output = "<l>Date:Ago:Week2</l>"; break;
                    case 7: $Output = "<l>Date:Ago:Week</l>"; break;
                    case 2: $Output = "<l>Date:Ago:Yesterday2</l>"; break;
                    case 1: $Output = "<l>Date:Ago:Yesterday</l>"; break;
                    case 0: $Output = "<l>Date:Ago:Today</l>"; break;
                }
        }
        
        return $Output;
    }
    
    