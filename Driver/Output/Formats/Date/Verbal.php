<?php
	
    function F_Verbal_Format($Date)
    {
            $VerbalTimes = file_get_contents(Engine.'_Shared/Static/russian.csv');
            $VerbalTimes = explode("\n",$VerbalTimes);
            return $VerbalTimes[(date('G', $Date)*60)+(date('i', $Date))];
    }