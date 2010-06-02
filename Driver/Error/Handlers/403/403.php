<?php

    function F_403_Catch()
    {
        header ('HTTP/1.1 403 Forbidden');
        die (Page::Load('Errors/403'));
    }