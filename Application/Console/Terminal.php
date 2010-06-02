<?php

    if (Server::$REST == 'post'&&Client::$UID == 'BreathLess')
    {
        $Pieces = explode(' ',Server::Get('CMD'));

        $Namespace = $Pieces[0];
        $Function = $Pieces[1];

        $Output = Code::E('Console/'.$Namespace, $Function, $Pieces);

        Page::Body('<div class="Caption"> <icon>Console/In</icon> '.Server::Get('CMD').'</div><div class="Block"> <icon>Console/Out</icon> '.$Output.'</div>');
        /*
         * app install application shared plugin

        app install application self plugin
        app create application
         * */
    }