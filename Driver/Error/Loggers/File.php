<?php

    function F_File_Output($Messages)
    {
        $Output = Server::Get('REQUEST_TIME').'@'.Server::Get('REQUEST_URI')."\n\n";

        foreach($Messages as $Message)
            $Output.= $Message[0]."\x9".Log::$Types[$Message[1]]."\x9".$Message[2]."\n";

		$LogFile = '/var/log/vxi/'.$_SERVER['SERVER_NAME'].'.log';
        
        if (!file_exists($LogFile))
            touch($LogFile);

        $F = fopen($LogFile, 'a+');
		if ($F)
        {
            fwrite($F, $Output);
            fclose($F);
        }
    }