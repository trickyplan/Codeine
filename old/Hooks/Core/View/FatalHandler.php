<?php

    function F_FatalHandler_Hook()
    {
        return ob_start('Server::FatalHandler');
    }