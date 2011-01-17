<?php

    function F_Default_Serialize($Args)
    {
        return serialize($Args);
    }

    function F_Default_Unserialize($Args)
    {
        return unserialize($Args);
    }