<?php

function F_StandartUID_Generate($Object)
    {
        return uniqid($Object->Scope);
    }