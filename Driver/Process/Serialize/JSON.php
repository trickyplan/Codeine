<?php

    function F_JSON_Serialize($Args)
    {
        return json_encode($Args);
    }

    function F_JSON_Unserialize($Args)
    {
        return json_decode($Args, true);
    }