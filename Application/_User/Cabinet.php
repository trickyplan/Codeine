<?php

    if (Client::$Authorized)    
        Page::Add(
            Page::Fusion('Objects/'.self::$Name.'/Cabinet', Client::$User)
                );
    else
        Page::Nest('Application/Gate/Guest');