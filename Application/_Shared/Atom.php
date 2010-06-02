<?php

    $Object = new Object(self::$Name, self::$Mode);
    
    if ($Object->Get('Owner') == Client::$UID)
        $Object->Set(self::$Name.':'.self::$ID, self::$Aspect);
    /* FIXME: Validation */

    $Object->Save();
    Page::Body('true');