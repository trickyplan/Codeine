<?php

    function F_File_Output($Messages)
    {
        $Output = '===='.date('YmdHis',Server::Get('REQUEST_TIME')).'@'.Server::Get('REQUEST_URI')."====\n";

        if (is_array($Messages) and !empty($Messages))
        {
            foreach($Messages as $AppID => $AppMessages)
                foreach($AppMessages as $Message)
                    $Output.= $AppID."\x9".$Message[0]."\x9".Log::$Types[$Message[1]]."\x9".$Message[2]."\n";

            $LogFile = '/var/log/codeine/'._SERVER.'.log';

            if (!file_exists($LogFile))
                touch($LogFile);

            $F = fopen($LogFile, 'a+');
                if ($F)
                {
                    fwrite($F, $Output);
                    fclose($F);
                }
        }

        return true;
    }