<?php

    $Object = new Object(self::$Name);

    if ($Object->Load(self::$ID))
        $Object->Set('Status', self::$Mode);

    $Object->Save();
    
    View::Add('<icon>Sidebar/Tick</icon> Готово');