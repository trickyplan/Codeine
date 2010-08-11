<?php

function F_StrongUID_Generate($Object)
    {
        return uniqid($Object->Scope,true);
    }