<?php

    function F_Auto_Decode($Args)
    {
        if (mb_substr($Args, 0, 5)=='<?xml')
        {
            if ($data = simplexml_load_string ($Args))
                return $data;
        }
        else
        {
            if ($data = json_decode($Args))
                return $data;
        }

        // TODO: YAML?

        return null;
    }