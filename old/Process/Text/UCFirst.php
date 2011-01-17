<?php

    function F_UCFirst_Process($String)
    {
        if (mb_check_encoding($String,'UTF-8'))
            return mb_substr(mb_strtoupper($String, 'utf-8'),0,1,'utf-8').mb_substr(mb_strtolower($String,'utf-8'),1,mb_strlen($String),'utf-8');
        else
            return $String;
   }
   
