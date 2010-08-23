<?php

    function F_Codeine_ObjectSerializer ($Object)
    {
        return $Object->Scope.OBJSEP.$Object->Name;
    }