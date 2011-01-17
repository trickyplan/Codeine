<?php

    if (Client::$Level > 0)
        View::Add(
            View::Fusion('Objects/'.self::$Name.'/Cabinet', Client::$User)
                );
    else
        View::Nest('Application/Gate/Guest');