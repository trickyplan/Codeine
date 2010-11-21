<?php

    $Filter = function ($Data)
	{       		
        $Filtered = array();

        if (is_array($Data))
            foreach($Data as $Key => $Value)
                $Filtered[urldecode($Key)] = $Value;
        else
            {
                $Filtered = urldecode($Data);
                $Filtered = htmlspecialchars($Filtered);
            }

        return $Filtered;
	};