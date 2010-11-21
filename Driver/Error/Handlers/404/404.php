<?php

    function F_404_Catch()
    {
        header ('HTTP/1.1 404 Not Found');
        die (View::Load('Errors/404'));
    }