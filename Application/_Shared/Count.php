<?php

if (!self::$Collection->Queried)
    self::$Collection->Query(self::$ID);

Page::Body(count(self::$Collection->Names));