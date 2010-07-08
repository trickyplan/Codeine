<?php

    function F_Printrt_Serialize($Args)
    {
        return print_r($Args,true);
    }

    function F_Printr_Unserialize($Args)
    {
        return Log::Error('Not implemented');
    }