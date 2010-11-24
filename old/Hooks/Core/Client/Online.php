<?php

    function F_Online_Hook()
    {
        if (Client::$Ticket->Get('LastHit')<(time()-60))
                    Client::$Ticket->Set('LastHit', time());
        
        if (Client::$Level > 0)
            if (Client::$Agent->Get('LastHit')<(time()-60))
            {
                Client::$Agent->Set('LastHit', time());
                if ((Client::$Agent->Get('Online') == 'False') or (Client::$Agent->Get('Online') === null))
                    Client::$Agent->Set('Online', 'True');
            }
    }