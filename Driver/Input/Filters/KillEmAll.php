<?php

function F_KillEmAll_Filter($Data)
	{       		
            $Filtered = array();
            
            if (is_array($Data))
                foreach($Data as $Key => $Value)
                    $Filtered[urldecode($Key)] = F_KillEmAll_Filter($Value);
            else
                {
                    $Filtered = urldecode($Data);
                    $Filtered = htmlspecialchars($Filtered);
                }

            return $Filtered;
	};