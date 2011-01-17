<?php

function F_EMail_Check($Args)
{
        $R = array_diff($Args['True'], $Args['Challenge']);
        if (empty($R))
            return true;
        else
            return false;
    }
