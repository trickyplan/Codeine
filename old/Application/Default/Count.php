<?php

if (!self::$Collection->Queried)
    self::$Collection->Query(self::$ID);

View::Body(count(self::$Collection->Names));