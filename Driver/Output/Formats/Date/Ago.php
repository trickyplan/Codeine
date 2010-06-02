<?php

    function F_Ago_Format ($Date)
    {
        $Future = false;
        $Output = array();
        
        $Els = array('Days','Hours', 'Mins', 'Secs');

        foreach ($Els as $El)
        {
            $DT[$El] = 0;
            $Output[$El] = '';
        }
        
        $DT['Secs'] = time() - $Date;

        if ($DT['Secs']<0)
            $Future = true;
        
        $DT['Secs'] = abs($DT['Secs']);
        
        if ($DT['Secs'] >= 60)
        {
            $DT['Mins'] = floor($DT['Secs']/60);
            $DT['Secs'] = $DT['Secs']%60;
        }

        if ($DT['Mins'] >= 60)
        {
            $DT['Hours'] = floor($DT['Mins']/60);
            $DT['Mins']  = $DT['Mins']%60;
        }

        if ($DT['Hours'] >= 24)
        {
            $DT['Days'] = floor($DT['Hours']/24);
            $DT['Hours'] = $DT['Hours']%24;
        }

        foreach ($Els as $El)
        {
            if ($DT[$El] %10 == 1 and $DT[$El] != 11)
                 $Output[$El] = $DT[$El].' <l>Date:'.$El.':1</l>';
            elseif (($DT[$El]%10 >= 2) && ($DT[$El] <= 4))
                 $Output[$El] = $DT[$El].' <l>Date:'.$El.':2</l>';
            elseif (($DT[$El]>10) && ($DT[$El]<20))
                 $Output[$El] = $DT[$El].' <l>Date:'.$El.'</l>';
            else
                 $Output[$El] = $DT[$El].' <l>Date:'.$El.'</l>';
        }
        
        if (!$Future) 
            $OutputStr = ''.implode(' ', $Output).' <l>Date:Ago</l>';
        else
            $OutputStr = '<l>Date:Future</l> '.implode(' ', $Output);
			
        return $OutputStr;
    }
    
    