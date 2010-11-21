<?php

    function F_YMD2DM_Format($Date)
    {
        return date('d.m', mktime(0,0,0,mb_substr($Date,4,2), mb_substr($Date,6,2), mb_substr($Date,0,4)));
    }