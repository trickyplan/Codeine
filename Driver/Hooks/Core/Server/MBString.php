<?php

    function F_MBString_Hook()
    {
        mb_regex_encoding('UTF-8');
        mb_regex_set_options('dim');
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');

        return true;
    }