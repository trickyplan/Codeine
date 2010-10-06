<?php
	
    function F_Verbal_Format($Date)
    {
        $VerbalTimes = file_get_contents(Server::Locate('Static', Client::$Language.'.csv'));
        $VerbalTimes = explode("\n",$VerbalTimes);
        return $VerbalTimes[(date('G', $Date)*60)+(date('i', $Date))];
    }