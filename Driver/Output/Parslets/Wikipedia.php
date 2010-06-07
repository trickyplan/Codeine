<?php

    function F_Wikipedia_Parse ($Input)
    {
        $WL = mb_substr(Client::$Language,0,2);
        return 'http://'.$WL.'.wikipedia.org/wiki/'.$Input;
    }
    
    