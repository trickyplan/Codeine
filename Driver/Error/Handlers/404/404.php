<?php

    function F_404_Catch()
    {
        header ('HTTP/1.1 404 Not Found');
        die (Page::Load('Errors/404'));
    }